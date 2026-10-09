<?php

declare(strict_types=1);

namespace App\Http\Controllers\Countings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Countings\StoreCountingRequest;
use App\Http\Requests\Countings\StoreCountingRoundRequest;
use App\Models\Auth\Organization;
use App\Models\Countings\Counting;
use App\Models\Countings\CountingRound;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductLocation;
use App\Models\User;
use App\Services\Countings\CountingResolutionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Admin surface del módulo tomfic-field: crear tomas, programar rondas
 * C1/C2, abrir C3 cuando hay discrepancia, resolver y cerrar la toma.
 * El core de inventario (StockAudit*) NO se toca desde aquí.
 */
class CountingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CountingResolutionService $resolver)
    {
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Counting::class);

        $countings = Counting::withCount('rounds')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Countings/Index', [
            'countings' => $countings,
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', Counting::class);

        $organizationId = $request->user()->organization_id;

        return Inertia::render('Countings/Create', [
            'locations' => ProductLocation::forOrganization($organizationId)
                ->active()
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
            'capturadores' => User::where('organization_id', $organizationId)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(StoreCountingRequest $request): RedirectResponse
    {
        $this->authorize('create', Counting::class);

        $counting = Counting::create([
            'organization_id' => $request->user()->organization_id,
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'status' => 'draft',
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('countings.show', $counting)
            ->with('success', 'Toma creada. Ahora programa las rondas C1 y C2.');
    }

    public function show(Request $request, Counting $counting): Response
    {
        $this->authorize('view', $counting);

        $counting->load([
            'rounds' => fn ($q) => $q->with(['location', 'assignments.user', 'items.product']),
        ]);

        $resolved = $this->resolver->resolve($counting);
        $discrepancies = $this->resolver->discrepancies($counting);

        return Inertia::render('Countings/Show', [
            'counting' => $counting,
            'resolved' => $resolved,
            'discrepancies' => $discrepancies,
            'canClose' => $this->resolver->canClose($counting),
        ]);
    }

    /**
     * Programa una ronda nueva (c1/c2/c3) en una ubicación, con capturadores asignados.
     */
    public function storeRound(StoreCountingRoundRequest $request, Counting $counting): RedirectResponse
    {
        $this->authorize('manage', $counting);

        $validated = $request->validated();

        $organizationId = $request->user()->organization_id;
        $location = ProductLocation::where('id', $validated['product_location_id'])
            ->forOrganization($organizationId)
            ->firstOrFail();

        // Capturadores deben pertenecer a la organización de la toma.
        $validUserIds = User::where('organization_id', $organizationId)
            ->whereIn('id', $validated['capturador_ids'])
            ->pluck('id');

        // C3 solo se abre si hay discrepancia con C1/C2 cerradas (regla del negocio).
        if ($validated['round_type'] === 'c3') {
            $disputes = $this->resolver->discrepancies($counting);
            if ($disputes->where('product_location_id', $location->id)->isEmpty()) {
                return back()->with('error', 'C3 solo se abre cuando C1 y C2 difieren en esa ubicación.');
            }
        }

        DB::transaction(function () use ($counting, $validated, $validUserIds) {
            $round = CountingRound::updateOrCreate(
                [
                    'counting_id' => $counting->id,
                    'product_location_id' => $validated['product_location_id'],
                    'round_type' => $validated['round_type'],
                ],
                ['status' => 'pending'],
            );

            $round->assignments()->delete();
            foreach ($validUserIds as $userId) {
                $round->assignments()->create(['user_id' => $userId]);
            }

            if ($counting->status === 'draft') {
                $counting->update(['status' => 'in_progress']);
            }
        });

        return back()->with('success', 'Ronda programada con capturadores asignados.');
    }

    public function openRound(Request $request, CountingRound $round): RedirectResponse
    {
        $this->authorize('manage', $round->counting);

        if ($round->status !== 'pending') {
            return back()->with('error', 'Solo una ronda pending puede abrirse.');
        }

        $round->update(['status' => 'open']);

        return back()->with('success', 'Ronda abierta para captura.');
    }

    public function closeRound(Request $request, CountingRound $round): RedirectResponse
    {
        $this->authorize('manage', $round->counting);

        if ($round->status !== 'open') {
            return back()->with('error', 'Solo una ronda open puede cerrarse.');
        }

        $round->update(['status' => 'closed']);
        $counting = $round->counting;

        // C1 y C2 cerradas con discrepancia: señalar que se requiere C3.
        $disputes = $this->resolver->discrepancies($counting);
        $needC3 = $disputes->isNotEmpty()
            && $counting->rounds()->where('round_type', 'c3')->doesntExist();

        if ($needC3) {
            $locations = $disputes->pluck('product_location_id')->unique();

            return back()->with('warning',
                "Discrepancias en {$locations->count()} ubicación(es). Abre ronda C3 (árbitro) para resolverlas.");
        }

        return back()->with('success', 'Ronda cerrada.');
    }

    /**
     * Cierra la toma (solo si el motor lo permite: sin rondas abiertas y sin disputas sin árbitro).
     */
    public function close(Request $request, Counting $counting): RedirectResponse
    {
        $this->authorize('manage', $counting);

        if (! $this->resolver->canClose($counting)) {
            return back()->with('error',
                'No se puede cerrar: hay rondas abiertas o discrepancias sin resolver con C3.');
        }

        $counting->update(['status' => 'closed']);

        return back()->with('success', 'Toma cerrada. Los valores finales están en la sección de resultados.');
    }

    /**
     * Productos activos de la org para la vista de captura (lectura del catálogo core).
     */
    public function products(Request $request, Counting $counting): \Illuminate\Http\JsonResponse
    {
        $this->authorize('capture', $counting);

        $products = Product::forOrganization($request->user()->organization_id)
            ->active()
            ->orderBy('name')
            ->limit(500)
            ->get(['id', 'sku', 'name']);

        return response()->json(['products' => $products]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Countings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Countings\CaptureCountingItemRequest;
use App\Models\Countings\CountingItem;
use App\Models\Countings\CountingRound;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Captura en campo (rol capturador): la PWA consumirá estos endpoints.
 * Regla dura: re-escanear el mismo producto REEMPLAZA (upsert por unique round+product),
 * y un capturador solo puede escribir en rondas a él asignadas.
 */
class RoundCaptureController extends Controller
{
    use AuthorizesRequests;

    /**
     * Las rondas asignadas al capturador autenticado. Nunca expone items de otros.
     */
    public function myRounds(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user->hasPermission('capture_countings'), 403,
            'No tienes permiso de captura.');

        $rounds = CountingRound::forCapturador($user->id)
            ->with(['location:id,name,code', 'counting:id,name,status'])
            ->get(['id', 'counting_id', 'product_location_id', 'round_type', 'status']);

        return response()->json(['rounds' => $rounds]);
    }

    /**
     * Vista de UNA ronda para su capturador asignado:
     * sus items ya capturados, sin NINGÚN dato de la ronda hermana (C1 a ciegas de C2).
     */
    public function show(Request $request, CountingRound $round): JsonResponse
    {
        $user = $request->user();

        $isAssigned = $round->assignments()->where('user_id', $user->id)->exists();
        abort_unless($isAssigned, 403, 'Esta ronda no está asignada a ti.');

        // A ciegas: solo los items de ESTA ronda.
        $items = $round->items()->with('product:id,sku,name')->get();

        return response()->json([
            'round' => $round->only(['id', 'round_type', 'status']),
            'items' => $items,
        ]);
    }

    /**
     * Captura con reemplazo (re-scan = upsert) + foto de evidencia opcional.
     */
    public function capture(CaptureCountingItemRequest $request, CountingRound $round): JsonResponse
    {
        $user = $request->user();

        $isAssigned = $round->assignments()->where('user_id', $user->id)->exists();
        abort_unless($isAssigned, 403, 'Esta ronda no está asignada a ti.');

        if (! in_array($round->status, ['open', 'pending'], true)) {
            return response()->json(['error' => 'La ronda no acepta capturas.'], 422);
        }

        if ($round->status === 'pending') {
            $round->update(['status' => 'open']);
        }

        $validated = $request->validated();
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store("countings/{$round->counting_id}", 'public');
        }

        // Reemplazo por unique(round, product): re-scan actualiza, nunca duplica.
        $item = CountingItem::updateOrCreate(
            ['counting_round_id' => $round->id, 'product_id' => $validated['product_id']],
            [
                'units' => $validated['units'],
                'boxes' => $validated['boxes'] ?? 0,
                'counted_by' => $user->id,
                ...($photoPath ? ['photo_path' => $photoPath] : []),
            ]
        );

        return response()->json([
            'item' => $item->load('product:id,sku,name'),
            'replaced' => $item->wasRecentlyCreated === false,
        ]);
    }

    /**
     * El capturador cierra su ronda cuando termina su ronda física.
     */
    public function finish(Request $request, CountingRound $round): JsonResponse
    {
        $user = $request->user();

        $isAssigned = $round->assignments()->where('user_id', $user->id)->exists();
        abort_unless($isAssigned, 403, 'Esta ronda no está asignada a ti.');

        if ($round->status !== 'open') {
            return response()->json(['error' => 'Solo una ronda open puede terminar.'], 422);
        }

        $round->update(['status' => 'closed']);

        return response()->json(['ok' => true]);
    }
}

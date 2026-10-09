<?php

declare(strict_types=1);

namespace Tests\Feature\Countings;

use App\Models\Auth\Organization;
use App\Models\Countings\Counting;
use App\Models\Countings\CountingItem;
use App\Models\Countings\CountingRound;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductLocation;
use App\Models\System\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * HTTP surface del módulo Countings (tomfic-field): ciclo de vida completo
 * por rutas reales, con reglas del negocio (C3 solo con disputa, capturador
 * solo su ronda, re-scan reemplaza).
 */
class CountingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private Organization $org;
    private User $admin;
    private User $capturador1;
    private User $capturador2;
    private ProductLocation $location;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        SystemSetting::set('installed', true, 'boolean');

        $this->org = Organization::factory()->create();

        // role='admin' => isAdmin() => todos los permisos (mismo patrón que StockAuditConcurrencyTest)
        $this->admin = User::factory()->create(['organization_id' => $this->org->id, 'role' => 'admin']);
        $this->capturador1 = User::factory()->create(['organization_id' => $this->org->id, 'role' => 'admin']);
        $this->capturador2 = User::factory()->create(['organization_id' => $this->org->id, 'role' => 'admin']);

        $this->location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $this->product = Product::factory()->create(['organization_id' => $this->org->id]);
    }

    public function test_admin_creates_counting_and_programs_both_rounds(): void
    {
        $countingId = null;

        $this->actingAs($this->admin)
            ->post(route('countings.store'), [
                'name' => 'Cierre octubre',
                'description' => 'Toma mensual',
            ])
            ->assertRedirect();

        $counting = Counting::where('name', 'Cierre octubre')->firstOrFail();
        $this->assertSame('draft', $counting->status);
        $countingId = $counting->id;

        // Programa C1 y C2 con capturadores distintos
        foreach ([['c1', $this->capturador1->id], ['c2', $this->capturador2->id]] as [$type, $userId]) {
            $this->actingAs($this->admin)
                ->post(route('countings.rounds.store', $counting), [
                    'product_location_id' => $this->location->id,
                    'round_type' => $type,
                    'capturador_ids' => [$userId],
                ])
                ->assertRedirect()
                ->assertSessionHas('success');
        }

        $this->assertSame(2, $counting->rounds()->count());
        $this->assertSame('in_progress', $counting->fresh()->status);
        $this->assertSame(
            [$this->capturador1->id],
            $counting->rounds()->where('round_type', 'c1')->first()->assignments()->pluck('user_id')->all(),
        );
    }

    public function test_c3_is_rejected_without_discrepancy(): void
    {
        $counting = Counting::create([
            'organization_id' => $this->org->id,
            'name' => 'Sola',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('countings.rounds.store', $counting), [
                'product_location_id' => $this->location->id,
                'round_type' => 'c3',
                'capturador_ids' => [$this->capturador1->id],
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame(0, $counting->rounds()->count(),
            'C3 no debe existir sin disputa previa C1/C2');
    }

    public function test_full_lifecycle_with_c3_arbitration(): void
    {
        $counting = Counting::create([
            'organization_id' => $this->org->id,
            'name' => 'Ciclo completo',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        // C1 y C2
        foreach ([['c1', $this->capturador1->id], ['c2', $this->capturador2->id]] as [$type, $userId]) {
            $this->actingAs($this->admin)->post(route('countings.rounds.store', $counting), [
                'product_location_id' => $this->location->id,
                'round_type' => $type,
                'capturador_ids' => [$userId],
            ]);
        }

        $c1 = $counting->rounds()->where('round_type', 'c1')->first();
        $c2 = $counting->rounds()->where('round_type', 'c2')->first();

        // Abrir cierre: capturador1 captura 50 en C1, capturador2 captura 48 en C2
        $this->actingAs($this->capturador1)
            ->postJson(route('countings.rounds.capture', $c1), ['product_id' => $this->product->id, 'units' => 50])
            ->assertOk();

        $this->actingAs($this->capturador2)
            ->postJson(route('countings.rounds.capture', $c2), ['product_id' => $this->product->id, 'units' => 48])
            ->assertOk();

        // Re-scan del mismo capturador en SU ronda: reemplaza, no duplica
        $this->actingAs($this->capturador1)
            ->postJson(route('countings.rounds.capture', $c1), ['product_id' => $this->product->id, 'units' => 50])
            ->assertOk();
        $this->assertSame(1, $c1->items()->count());

        // Admin cierra rondas (el capturador terminó de capturar; aún open)
        $this->actingAs($this->admin)
            ->post(route('countings.rounds.close', $c1))
            ->assertRedirect()
            ->assertSessionHas('success');
        $response = $this->actingAs($this->admin)->post(route('countings.rounds.close', $c2));
        $response->assertRedirect()->assertSessionHas('warning');

        // Las vistas del otro capturador no filtran: cada quien SOLO su ronda
        $this->actingAs($this->capturador1)
            ->getJson(route('countings.rounds.show', $c2))
            ->assertForbidden();

        // Ahora C3 sí se puede abrir
        $this->actingAs($this->admin)
            ->post(route('countings.rounds.store', $counting), [
                'product_location_id' => $this->location->id,
                'round_type' => 'c3',
                'capturador_ids' => [$this->capturador1->id],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $c3 = $counting->rounds()->where('round_type', 'c3')->first();
        $this->assertNotNull($c3, 'C3 debe existir tras la disputa');

        // C3=49 manda SIEMPRE
        $this->actingAs($this->capturador1)
            ->postJson(route('countings.rounds.capture', $c3), ['product_id' => $this->product->id, 'units' => 49])
            ->assertOk();

        $this->actingAs($this->admin)->post(route('countings.rounds.close', $c3))
            ->assertRedirect()
            ->assertSessionHas('success');

        // Cerrar la toma: puede y queda closed
        $this->actingAs($this->admin)
            ->post(route('countings.close', $counting))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('closed', $counting->fresh()->status);
        $this->assertSame(49, (int) $c3->items()->first()->units);
    }

    public function test_capturador_outside_org_cannot_capture(): void
    {
        $otherOrg = Organization::factory()->create();
        $outsider = User::factory()->create(['organization_id' => $otherOrg->id, 'role' => 'admin']);

        $counting = Counting::create([
            'organization_id' => $this->org->id,
            'name' => 'Secured',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $round = CountingRound::create([
            'counting_id' => $counting->id,
            'product_location_id' => $this->location->id,
            'round_type' => 'c1',
            'status' => 'open',
        ]);

        $this->actingAs($outsider)
            ->postJson(route('countings.rounds.capture', $round), ['product_id' => $this->product->id, 'units' => 5])
            ->assertForbidden();

        $this->assertSame(0, $round->items()->count());
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Countings;

use App\Models\Auth\Organization;
use App\Models\Countings\Counting;
use App\Models\Countings\CountingItem;
use App\Models\Countings\CountingRound;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductLocation;
use App\Models\User;
use App\Services\Countings\CountingResolutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresión del fix A1 (canClose por ubicación) y A2 (multiplicación boxes × units_per_box).
 * Fuente: debate evaluacion-proyecto-debate 2026-10-10.
 */
class CountingRegressionFixesTest extends TestCase
{
    use RefreshDatabase;

    private CountingResolutionService $service;
    private Organization $org;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CountingResolutionService;
        $this->org = Organization::factory()->create();
        $this->admin = User::factory()->create(['organization_id' => $this->org->id]);
        $this->actingAs($this->admin);
    }

    private function closedRoundWithItem(Counting $counting, ProductLocation $location, string $type, Product $product, int $units, int $boxes = 0): CountingRound
    {
        $round = CountingRound::create([
            'counting_id' => $counting->id,
            'product_location_id' => $location->id,
            'round_type' => $type,
            'status' => 'open',
        ]);
        CountingItem::create([
            'counting_round_id' => $round->id,
            'product_id' => $product->id,
            'units' => $units,
            'boxes' => $boxes,
            'counted_by' => $this->admin->id,
        ]);
        $round->update(['status' => 'closed']);

        return $round;
    }

    public function test_can_close_blocked_when_c3_closed_only_in_another_location(): void
    {
        $locA = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $locB = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $productA = Product::factory()->create(['organization_id' => $this->org->id]);
        $productB = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = Counting::create([
            'organization_id' => $this->org->id, 'name' => 'Toma multi',
            'status' => 'in_progress', 'created_by' => $this->admin->id,
        ]);

        // Ubicación A: disputa arbitrada por C3 cerrada.
        $this->closedRoundWithItem($counting, $locA, 'c1', $productA, 50);
        $this->closedRoundWithItem($counting, $locA, 'c2', $productA, 48);
        $this->closedRoundWithItem($counting, $locA, 'c3', $productA, 49);

        // Ubicación B: disputa SIN C3 (sin árbitro).
        $this->closedRoundWithItem($counting, $locB, 'c1', $productB, 10);
        $this->closedRoundWithItem($counting, $locB, 'c2', $productB, 9);

        // El escape anterior (C3 exists() global) devolvía true: la toma se cerraba
        // con la B sin arbitrar. Ahora debe bloquearse.
        $this->assertFalse($this->service->canClose($counting));

        // Al cerrar la C3 de la B, ya se puede cerrar la toma completa.
        $this->closedRoundWithItem($counting, $locB, 'c3', $productB, 9);
        $this->assertTrue($this->service->canClose($counting));
    }

    public function test_cajas_multiplied_by_units_per_box_plus_loose_units(): void
    {
        $loc = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        // 10 cajas × 12 + 2 sueltas = 122 (fórmula del dueño).
        $product = Product::factory()->create(['organization_id' => $this->org->id, 'units_per_box' => 12]);
        $counting = Counting::create([
            'organization_id' => $this->org->id, 'name' => 'Toma cajas',
            'status' => 'in_progress', 'created_by' => $this->admin->id,
        ]);

        $c1 = $this->closedRoundWithItem($counting, $loc, 'c1', $product, 2, 10);
        $this->closedRoundWithItem($counting, $loc, 'c2', $product, 122);

        $resolved = $this->service->resolve($counting);
        $this->assertCount(1, $resolved);
        $entry = $resolved->first();
        $this->assertSame(122, $entry['final_units']);
        $this->assertSame('c1_c2_agree', $entry['source']);
    }

    public function test_default_units_per_box_of_1_keeps_legacy_behaviour(): void
    {
        $loc = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        // Producto sin units_per_box configurado (default 1): 3 cajas + 2 sueltas = 5.
        $product = Product::factory()->create(['organization_id' => $this->org->id, 'units_per_box' => 1]);
        $counting = Counting::create([
            'organization_id' => $this->org->id, 'name' => 'Toma default',
            'status' => 'in_progress', 'created_by' => $this->admin->id,
        ]);

        $this->closedRoundWithItem($counting, $loc, 'c1', $product, 2, 3);
        $this->closedRoundWithItem($counting, $loc, 'c2', $product, 5);

        $resolved = $this->service->resolve($counting);
        $this->assertSame('c1_c2_agree', $resolved->first()['source']);
    }

    public function test_discrepancy_only_counts_the_external_value_from_units_per_box(): void
    {
        $loc = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        // 2 cajas × 6 + 0 = 12 vs 10 sueltas → discrepancia por 2 unidades (antes habría empatado mal).
        $product = Product::factory()->create(['organization_id' => $this->org->id, 'units_per_box' => 6]);
        $counting = Counting::create([
            'organization_id' => $this->org->id, 'name' => 'Toma disputa',
            'status' => 'in_progress', 'created_by' => $this->admin->id,
        ]);

        $this->closedRoundWithItem($counting, $loc, 'c1', $product, 0, 2);
        $this->closedRoundWithItem($counting, $loc, 'c2', $product, 10);

        $disputes = $this->service->discrepancies($counting);
        $this->assertCount(1, $disputes);
        $this->assertSame(12, $disputes->first()['c1_units']);
        $this->assertSame(10, $disputes->first()['c2_units']);
    }
}

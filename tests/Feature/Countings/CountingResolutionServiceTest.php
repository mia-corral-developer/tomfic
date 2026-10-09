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

class CountingResolutionServiceTest extends TestCase
{
    use RefreshDatabase;

    private Organization $org;
    private User $admin;
    private CountingResolutionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CountingResolutionService;
        $this->org = Organization::factory()->create();
        $this->admin = User::factory()->create(['organization_id' => $this->org->id]);

        // BelongsToOrganization estampa organization_id desde el usuario autenticado:
        // hacemos login como admin del org para todas las operaciones del módulo.
        $this->actingAs($this->admin);
    }

    private function makeCounting(): Counting
    {
        return Counting::create([
            'organization_id' => $this->org->id,
            'name' => 'Cierre octubre',
            'status' => 'in_progress',
            'created_by' => $this->admin->id,
        ]);
    }

    private function makeRound(Counting $counting, ProductLocation $location, string $type, string $status = 'open'): CountingRound
    {
        return CountingRound::create([
            'counting_id' => $counting->id,
            'product_location_id' => $location->id,
            'round_type' => $type,
            'status' => $status,
        ]);
    }

    private function capture(CountingRound $round, Product $product, int $units, int $boxes = 0): CountingItem
    {
        return CountingItem::updateOrCreate(
            ['counting_round_id' => $round->id, 'product_id' => $product->id],
            ['units' => $units, 'boxes' => $boxes, 'counted_by' => $this->admin->id]
        );
    }

    public function test_c1_equals_c2_makes_that_value_final(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $this->capture($c1, $product, 50);
        $this->capture($c2, $product, 50);
        $c1->update(['status' => 'closed']);
        $c2->update(['status' => 'closed']);

        $resolved = $this->service->resolve($counting);

        $this->assertCount(1, $resolved);
        $entry = $resolved->first();
        $this->assertSame(50, $entry['final_units']);
        $this->assertSame('c1_c2_agree', $entry['source']);
        $this->assertTrue($this->service->discrepancies($counting)->isEmpty());
    }

    public function test_c1_differs_from_c2_reports_discrepancy_without_final(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $this->capture($c1, $product, 50);
        $this->capture($c2, $product, 48);
        $c1->update(['status' => 'closed']);
        $c2->update(['status' => 'closed']);

        $resolved = $this->service->resolve($counting);
        $this->assertCount(0, $resolved, 'Discrepancia sin C3: no hay valor final');

        $disputed = $this->service->discrepancies($counting);
        $this->assertCount(1, $disputed);
        $this->assertSame(50, $disputed->first()['c1_units']);
        $this->assertSame(48, $disputed->first()['c2_units']);
    }

    public function test_c1_differs_but_c3_closed(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $c3 = $this->makeRound($counting, $location, 'c3');
        $this->capture($c1, $product, 50);
        $this->capture($c2, $product, 48);
        $this->capture($c3, $product, 49);
        $c1->update(['status' => 'closed']);
        $c2->update(['status' => 'closed']);
        $c3->update(['status' => 'closed']);

        $resolved = $this->service->resolve($counting);

        // C3 árbol SIEMPRE manda, aunque C1 dijera 50.
        $this->assertCount(1, $resolved);
        $entry = $resolved->first();
        $this->assertSame(49, $entry['final_units']);
        $this->assertSame('c3_arbitration', $entry['source']);
        $this->assertSame(50, $entry['c1_units']);
        $this->assertSame(48, $entry['c2_units']);
        $this->assertTrue($this->service->discrepancies($counting)->isEmpty());
    }

    public function test_open_or_pending_rounds_are_not_counted(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1', 'open'); // aún abierta
        $this->capture($c1, $product, 50);

        $this->assertCount(0, $this->service->resolve($counting));
        $this->assertEmpty($this->service->discrepancies($counting));
    }

    public function test_rescan_replaces_quantity_not_duplicates(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $this->capture($c1, $product, 50);
        $this->capture($c1, $product, 30); // re-escaneo: reemplaza

        $this->assertSame(1, $c1->items()->count(), 'No debe duplicar el item');
        $this->assertSame(30, $c1->items()->first()->units);
    }

    public function test_boxes_added_to_units_for_normalization(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $this->capture($c1, $product, 20, 30); // 20 + 30 = 50
        $this->capture($c2, $product, 50);
        $c1->update(['status' => 'closed']);
        $c2->update(['status' => 'closed']);

        $resolved = $this->service->resolve($counting);
        $this->assertSame('c1_c2_agree', $resolved->first()['source'], '20+30 == 50: coinciden');
    }

    public function test_capturador_only_sees_own_rounds(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $capturador1 = User::factory()->create(['organization_id' => $this->org->id]);
        $capturador2 = User::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $c1->assignments()->create(['user_id' => $capturador1->id]);
        $c2->assignments()->create(['user_id' => $capturador2->id]);

        $ownRounds = CountingRound::forCapturador($capturador1->id)->pluck('id')->all();
        $this->assertSame([$c1->id], $ownRounds);
        $this->assertNotContains($c2->id, $ownRounds);
    }

    public function test_can_close_requires_all_rounds_closed_and_no_open_disputes(): void
    {
        $location = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $product = Product::factory()->create(['organization_id' => $this->org->id]);
        $counting = $this->makeCounting();

        $c1 = $this->makeRound($counting, $location, 'c1');
        $c2 = $this->makeRound($counting, $location, 'c2');
        $this->capture($c1, $product, 50);
        $this->capture($c2, $product, 48);
        $c1->update(['status' => 'closed']);

        // C2 abierta + discrepancia: no se puede cerrar.
        $this->assertFalse($this->service->canClose($counting));

        $c2->update(['status' => 'closed']);
        // Discrepancia sin C3: tampoco se puede cerrar.
        $this->assertFalse($this->service->canClose($counting));

        // C3 arbitra y cierra → ya se puede cerrar.
        $c3 = $this->makeRound($counting, $location, 'c3');
        $this->capture($c3, $product, 49);
        $c3->update(['status' => 'closed']);
        $this->assertTrue($this->service->canClose($counting));
    }
}

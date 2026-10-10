<?php

declare(strict_types=1);

namespace Tests\Feature\Countings;

use App\Models\Auth\Organization;
use App\Models\Countings\Counting;
use App\Models\Countings\CountingRound;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductLocation;
use App\Models\System\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * B2 del debate: el catálogo del capturador exponía solo id/sku/name sin barcode,
 * org-wide y con limit(500). Ahora: barcode + units_per_box arriba, filtro por
 * ubicación, búsqueda por exacto barcode y cursor pagination de 50.
 */
class CountingProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private Organization $org;
    private User $admin;
    private Counting $counting;
    private ProductLocation $locationA;
    private ProductLocation $locationB;

    protected function setUp(): void
    {
        parent::setUp();

        SystemSetting::set('installed', true, 'boolean');
        $this->org = Organization::factory()->create();
        $this->admin = User::factory()->create(['organization_id' => $this->org->id, 'role' => 'admin']);
        $this->actingAs($this->admin);

        $this->counting = Counting::create([
            'organization_id' => $this->org->id, 'name' => 'Toma catálogo',
            'status' => 'in_progress', 'created_by' => $this->admin->id,
        ]);
        $this->locationA = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
        $this->locationB = ProductLocation::factory()->create(['organization_id' => $this->org->id]);
    }

    public function test_catalog_exposes_barcode_and_units_per_box_with_pagination(): void
    {
        Product::factory()->create([
            'organization_id' => $this->org->id,
            'barcode' => '7701234567890',
            'units_per_box' => 12,
        ]);

        $response = $this->getJson(route('countings.products', $this->counting));

        $response->assertOk();
        $product = $response->json('products.data.0');
        $this->assertSame('7701234567890', $product['barcode']);
        $this->assertSame(12, $product['units_per_box']);
    }

    public function test_catalog_filters_by_location_and_barcode_lookup(): void
    {
        $inA = Product::factory()->create(['organization_id' => $this->org->id, 'location_id' => $this->locationA->id, 'barcode' => 'AAA111']);
        $inB = Product::factory()->create(['organization_id' => $this->org->id, 'location_id' => $this->locationB->id, 'barcode' => 'BBB222']);

        $this->getJson(route('countings.products', ['counting' => $this->counting, 'location_id' => $this->locationA->id]))
            ->assertOk()
            ->assertJsonCount(1, 'products.data');

        // La pistula busca por barcode exacto antes que por LIKE.
        $this->getJson(route('countings.products', ['counting' => $this->counting, 'search' => 'AAA111']))
            ->assertOk()
            ->assertJsonCount(1, 'products.data');
        $this->assertSame($inA->id, (int) $this->getJson(route('countings.products', ['counting' => $this->counting, 'search' => 'AAA111']))->json('products.data.0.id'));
    }
}

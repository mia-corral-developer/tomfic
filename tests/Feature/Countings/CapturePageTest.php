<?php

declare(strict_types=1);

namespace Tests\Feature\Countings;

use App\Models\Countings\Counting;
use App\Models\Countings\CountingItem;
use App\Models\System\SystemSetting;
use App\Models\Inventory\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapturePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_capture_page_renders_for_assigned_capturador(): void
    {
        $org = \App\Models\Auth\Organization::factory()->create();
        SystemSetting::set('installed', true, 'boolean');
        $admin = \App\Models\User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $capturador = \App\Models\User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $location = \App\Models\Inventory\ProductLocation::factory()->create(['organization_id' => $org->id]);
        $product = Product::factory()->create(['organization_id' => $org->id]);

        $this->actingAs($admin);
        $counting = Counting::create([
            'organization_id' => $org->id, 'name' => 'Toma X',
            'status' => 'in_progress', 'created_by' => $admin->id,
        ]);
        $round = \App\Models\Countings\CountingRound::create([
            'counting_id' => $counting->id, 'product_location_id' => $location->id,
            'round_type' => 'c1', 'status' => 'open',
        ]);
        $round->assignments()->create(['user_id' => $capturador->id]);
        CountingItem::create([
            'counting_round_id' => $round->id, 'product_id' => $product->id,
            'units' => 5, 'boxes' => 0, 'counted_by' => $capturador->id,
        ]);

        $this->actingAs($capturador)
            ->get(route('countings.rounds.show', $round))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Countings/Capture')
                ->has('round')
                ->has('items', 1));
    }

    public function test_capture_page_forbidden_for_unassigned_user(): void
    {
        $org = \App\Models\Auth\Organization::factory()->create();
        SystemSetting::set('installed', true, 'boolean');
        $stranger = \App\Models\User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $admin = \App\Models\User::factory()->create(['organization_id' => $org->id, 'role' => 'admin']);
        $location = \App\Models\Inventory\ProductLocation::factory()->create(['organization_id' => $org->id]);

        $this->actingAs($admin);
        $counting = Counting::create([
            'organization_id' => $org->id, 'name' => 'Toma Y',
            'status' => 'in_progress', 'created_by' => $admin->id,
        ]);
        $round = \App\Models\Countings\CountingRound::create([
            'counting_id' => $counting->id, 'product_location_id' => $location->id,
            'round_type' => 'c1', 'status' => 'open',
        ]);

        $this->actingAs($stranger)
            ->get(route('countings.rounds.show', $round))
            ->assertForbidden();
    }
}

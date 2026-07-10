<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Incident;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use App\Models\WaiterAssignment;
use App\Models\WaiterProfile;
use App\Models\WaiterShift;
use App\Models\WasteRecord;
use App\Models\Zone;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MesonerosModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_table_belongs_to_zone(): void
    {
        $zone = Zone::factory()->create();
        $table = Table::factory()->create(['zone_id' => $zone->id]);

        $this->assertInstanceOf(Zone::class, $table->zone);
        $this->assertEquals($zone->id, $table->zone->id);
    }

    public function test_waiter_assignment_tracks_table_waiter(): void
    {
        $waiter = User::factory()->create();
        $table = Table::factory()->create();
        $assignment = WaiterAssignment::factory()->create([
            'table_id' => $table->id,
            'waiter_id' => $waiter->id,
            'status' => 'active',
        ]);

        $this->assertTrue($assignment->waiter->is($waiter));
        $this->assertTrue($assignment->table->is($table));
    }

    public function test_waiter_shift_tracks_break(): void
    {
        $waiter = User::factory()->create();
        $shift = WaiterShift::factory()->create([
            'waiter_id' => $waiter->id,
            'status' => 'active',
            'is_on_break' => true,
        ]);

        $this->assertTrue($shift->is_on_break);
        $this->assertTrue($shift->waiter->is($waiter));
    }

    public function test_incident_creation(): void
    {
        $table = Table::factory()->create();
        $waiter = User::factory()->create();
        $incident = Incident::factory()->create([
            'table_id' => $table->id,
            'waiter_id' => $waiter->id,
            'type' => 'needs_help',
            'status' => 'open',
        ]);

        $this->assertEquals('needs_help', $incident->type);
        $this->assertEquals('open', $incident->status);
        $this->assertTrue($incident->table->is($table));
    }

    public function test_order_has_items(): void
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($order->items->contains($item));
    }

    public function test_order_item_scopes(): void
    {
        $pending = OrderItem::factory()->create(['status' => 'pending']);
        $preparing = OrderItem::factory()->create(['status' => 'preparing']);

        $this->assertTrue(OrderItem::pending()->get()->contains($pending));
        $this->assertFalse(OrderItem::pending()->get()->contains($preparing));
        $this->assertTrue(OrderItem::preparing()->get()->contains($preparing));
    }

    public function test_order_item_minutes_since_creation(): void
    {
        $item = OrderItem::factory()->create([
            'created_at' => now()->subMinutes(5),
        ]);

        $this->assertEquals(5, $item->minutesSinceCreation());
    }

    public function test_waiter_profile_has_user(): void
    {
        $user = User::factory()->create();
        $profile = WaiterProfile::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($profile->user->is($user));
    }

    public function test_detect_abandoned_command(): void
    {
        Table::factory()->create(['help_requested_at' => null, 'is_active' => true]);

        $this->artisan('mesoneros:detect-abandoned', ['--minutes' => '5'])
            ->assertExitCode(0);
    }
}

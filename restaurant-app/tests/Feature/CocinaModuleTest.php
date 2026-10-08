<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use App\Models\WasteRecord;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocinaModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_order_item_kitchen_note(): void
    {
        $item = OrderItem::factory()->create([
            'kitchen_note' => 'Sin sal',
            'status' => 'preparing',
        ]);

        $this->assertEquals('Sin sal', $item->kitchen_note);
    }

    public function test_order_item_return_to_kitchen(): void
    {
        $item = OrderItem::factory()->create([
            'status' => 'ready',
        ]);

        $item->returnToKitchen('Cliente no le gustó');

        $this->assertEquals('preparing', $item->fresh()->status);
        $this->assertEquals('Cliente no le gustó', $item->fresh()->return_reason);
        $this->assertNotNull($item->fresh()->returned_at);
    }

    public function test_order_priority(): void
    {
        $order = Order::factory()->create(['priority' => 'vip']);

        $this->assertEquals('vip', $order->priority);
    }

    public function test_waste_record_creation(): void
    {
        $dish = Dish::factory()->create();
        $user = User::factory()->create();

        $waste = WasteRecord::factory()->create([
            'dish_id' => $dish->id,
            'registered_by' => $user->id,
            'quantity' => 2,
            'reason' => 'overcooked',
        ]);

        $this->assertTrue($waste->dish->is($dish));
        $this->assertTrue($waste->registeredBy->is($user));
        $this->assertEquals(2, $waste->quantity);
    }

    public function test_order_item_timestamps(): void
    {
        $item = OrderItem::factory()->create([
            'status' => 'preparing',
            'started_at' => now()->subMinutes(10),
        ]);

        $this->assertNotNull($item->started_at);
        $this->assertEquals(10, $item->minutesInPreparation());
    }

    public function test_order_item_cancellation(): void
    {
        $item = OrderItem::factory()->create([
            'status' => 'pending',
        ]);

        $item->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => 'Sin stock',
        ]);

        $this->assertEquals('cancelled', $item->fresh()->status);
        $this->assertEquals('Sin stock', $item->fresh()->cancel_reason);
    }

    public function test_check_overdue_command(): void
    {
        OrderItem::factory()->create([
            'status' => 'preparing',
            'started_at' => now()->subMinutes(30),
        ]);

        $this->artisan('cocina:check-overdue', ['--minutes' => '15'])
            ->assertExitCode(0);
    }

    public function test_public_menu_page_loads(): void
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
    }

    public function test_order_payment(): void
    {
        $order = Order::factory()->create([
            'status' => 'ready',
            'total' => 50.00,
        ]);

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => 'cash',
        ]);

        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertEquals('cash', $order->fresh()->payment_method);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_cocina_tv_page_loads(): void
    {
        $response = $this->get('/cocina/tv');
        $response->assertStatus(200);
    }
}

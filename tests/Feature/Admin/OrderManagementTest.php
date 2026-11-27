<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_orders_with_customer_and_items(): void
    {
        $customer = $this->makeCustomer();
        $order = Order::factory()->create(['user_id' => $customer->id, 'total' => 200]);
        OrderItem::create(['order_id' => $order->id, 'name' => 'Green Apple', 'unit_price' => 100, 'quantity' => 2]);
        $admin = $this->actingAs($this->makeAdmin());

        $admin->get(route('admin.orders'))->assertOk()->assertSee($customer->name);
        $admin->get(route('admin.orders.show', $order))->assertOk()->assertSee('Green Apple')->assertSee($customer->email);
    }

    public function test_status_follows_allowed_transitions_and_records_history(): void
    {
        $admin = $this->makeAdmin();
        $order = Order::factory()->create();

        foreach (['confirmed', 'shipped', 'delivered'] as $status) {
            $this->actingAs($admin)->patch(route('admin.orders.status', $order), ['status' => $status])->assertRedirect(route('admin.orders.show', $order));
            $this->assertSame($status, $order->fresh()->status);
        }

        $this->assertDatabaseCount('order_status_histories', 3);
        $this->assertDatabaseHas('order_status_histories', ['order_id' => $order->id, 'from_status' => 'pending', 'to_status' => 'confirmed', 'changed_by' => $admin->id]);
    }

    public function test_invalid_transitions_are_rejected(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $admin = $this->actingAs($this->makeAdmin());

        $admin->patch(route('admin.orders.status', $order), ['status' => 'delivered'])->assertSessionHasErrors('status');
        $admin->patch(route('admin.orders.status', $order), ['status' => 'nonsense'])->assertSessionHasErrors('status');
        $this->assertSame('pending', $order->fresh()->status);

        $done = Order::factory()->create(['status' => 'delivered']);
        $admin->patch(route('admin.orders.status', $done), ['status' => 'pending'])->assertSessionHasErrors('status');
        $this->assertSame('delivered', $done->fresh()->status);
    }

    public function test_totals_and_customer_cannot_be_changed_through_the_status_endpoint(): void
    {
        $order = Order::factory()->create(['total' => 100]);
        $other = $this->makeCustomer();

        $this->actingAs($this->makeAdmin())->patch(route('admin.orders.status', $order), ['status' => 'confirmed', 'total' => 1, 'user_id' => $other->id]);

        $this->assertEquals(100, $order->fresh()->total);
        $this->assertNotSame($other->id, $order->fresh()->user_id);
    }

    public function test_customers_cannot_use_admin_order_pages(): void
    {
        $order = Order::factory()->create();

        $this->actingAs($this->makeCustomer())->get(route('admin.orders'))->assertForbidden();
        $this->actingAs($this->makeCustomer())->patch(route('admin.orders.status', $order), ['status' => 'confirmed'])->assertForbidden();
        $this->assertSame('pending', $order->fresh()->status);
    }
}

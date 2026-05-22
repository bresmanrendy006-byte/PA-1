<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function makeCustomer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    private function makePendingOrder(User $user): Order
    {
        $order = Order::factory()->create([
            'user_id'     => $user->id,
            'status'      => 'pending',
            'total_price' => 30000,
        ]);

        $menu = Menu::factory()->create(['price' => 15000]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'menu_id'  => $menu->id,
            'quantity' => 2,
            'price'    => 15000,
        ]);

        return $order;
    }

    // ======================
    // INDEX
    // ======================

    public function test_guest_cannot_access_orders()
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_see_orders_list()
    {
        $customer = $this->makeCustomer();
        $response = $this->actingAs($customer)->get('/orders');
        $response->assertStatus(200);
    }

    // ======================
    // SHOW
    // ======================

    public function test_customer_can_see_order_detail()
    {
        $customer = $this->makeCustomer();
        $order    = $this->makePendingOrder($customer);

        $response = $this->actingAs($customer)->get("/orders/{$order->id}");
        $response->assertStatus(200);
    }

    public function test_customer_cannot_see_other_users_order()
    {
        $customer1 = $this->makeCustomer();
        $customer2 = $this->makeCustomer();
        $order     = $this->makePendingOrder($customer1);

        $response = $this->actingAs($customer2)->get("/orders/{$order->id}");
        $response->assertStatus(404);
    }

    // ======================
    // CANCEL
    // ======================

    public function test_customer_can_cancel_pending_order()
    {
        $customer = $this->makeCustomer();
        $order    = $this->makePendingOrder($customer);

        $response = $this->actingAs($customer)
            ->delete("/orders/{$order->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_customer_cannot_cancel_confirmed_order()
    {
        $customer = $this->makeCustomer();
        $order    = Order::factory()->create([
            'user_id' => $customer->id,
            'status'  => 'confirmed',
        ]);

        $response = $this->actingAs($customer)
            ->delete("/orders/{$order->id}/cancel");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    // ======================
    // EDIT TO CART
    // ======================

    public function test_customer_can_edit_pending_order_to_cart()
    {
        $customer = $this->makeCustomer();
        $order    = $this->makePendingOrder($customer);

        $response = $this->actingAs($customer)
            ->post("/orders/{$order->id}/edit-to-cart");

        $response->assertRedirect('/cart');
        // Order lama dihapus
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        // Item masuk ke cart
        $this->assertDatabaseHas('carts', ['user_id' => $customer->id]);
    }

    public function test_customer_cannot_edit_confirmed_order_to_cart()
    {
        $customer = $this->makeCustomer();
        $order    = Order::factory()->create([
            'user_id' => $customer->id,
            'status'  => 'confirmed',
        ]);

        $response = $this->actingAs($customer)
            ->post("/orders/{$order->id}/edit-to-cart");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    // ======================
    // ADMIN CONFIRM & REJECT
    // ======================

    public function test_admin_can_confirm_order()
    {
        $owner    = User::factory()->create(['role' => 'owner']);
        $customer = $this->makeCustomer();
        $order    = $this->makePendingOrder($customer);

        $response = $this->actingAs($owner)
            ->post("/admin/orders/{$order->id}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_can_reject_order()
    {
        $owner    = User::factory()->create(['role' => 'owner']);
        $customer = $this->makeCustomer();
        $order    = $this->makePendingOrder($customer);

        $response = $this->actingAs($owner)
            ->post("/admin/orders/{$order->id}/reject");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'rejected',
        ]);
    }

    // ======================
    // DELETE
    // ======================

    public function test_customer_can_delete_rejected_order()
    {
        $customer = $this->makeCustomer();
        $order    = Order::factory()->create([
            'user_id' => $customer->id,
            'status'  => 'rejected',
        ]);

        $response = $this->actingAs($customer)
            ->delete("/orders/{$order->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function makeCustomer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    private function makeCart(User $user, int $quantity = 2): Cart
    {
        $menu = Menu::factory()->create(['price' => 15000]);
        return Cart::factory()->create([
            'user_id'  => $user->id,
            'menu_id'  => $menu->id,
            'quantity' => $quantity,
        ]);
    }

    // ======================
    // CART PAGE
    // ======================

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_access_cart()
    {
        $customer = $this->makeCustomer();
        $response = $this->actingAs($customer)->get('/cart');
        $response->assertStatus(200);
    }

    // ======================
    // UPDATE
    // ======================

    public function test_customer_can_update_cart_quantity()
    {
        $customer = $this->makeCustomer();
        $cart     = $this->makeCart($customer);

        $response = $this->actingAs($customer)
            ->put("/cart/update/{$cart->id}", ['quantity' => 5]);

        $response->assertRedirect();
        $this->assertDatabaseHas('carts', [
            'id'       => $cart->id,
            'quantity' => 5,
        ]);
    }

    public function test_customer_cannot_update_other_users_cart()
    {
        $customer1 = $this->makeCustomer();
        $customer2 = $this->makeCustomer();
        $cart      = $this->makeCart($customer1);

        $response = $this->actingAs($customer2)
            ->put("/cart/update/{$cart->id}", ['quantity' => 5]);

        $response->assertStatus(404);
    }

    // ======================
    // REMOVE
    // ======================

    public function test_customer_can_remove_cart_item()
    {
        $customer = $this->makeCustomer();
        $cart     = $this->makeCart($customer);

        $response = $this->actingAs($customer)
            ->delete("/cart/remove/{$cart->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_customer_cannot_remove_other_users_cart()
    {
        $customer1 = $this->makeCustomer();
        $customer2 = $this->makeCustomer();
        $cart      = $this->makeCart($customer1);

        $response = $this->actingAs($customer2)
            ->delete("/cart/remove/{$cart->id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
    }

    // ======================
    // CLEAR
    // ======================

    public function test_customer_can_clear_cart()
    {
        $customer = $this->makeCustomer();
        $this->makeCart($customer);
        $this->makeCart($customer);

        $response = $this->actingAs($customer)->delete('/cart/clear');

        $response->assertRedirect();
        $this->assertDatabaseMissing('carts', ['user_id' => $customer->id]);
    }

    // ======================
    // CHECKOUT
    // ======================

    public function test_customer_can_checkout()
    {
        $customer = $this->makeCustomer();
        $this->makeCart($customer);

        $response = $this->actingAs($customer)
            ->post('/orders/checkout');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'status'  => 'pending',
        ]);
        // Cart harus kosong setelah checkout
        $this->assertDatabaseMissing('carts', ['user_id' => $customer->id]);
    }

    public function test_customer_cannot_checkout_with_empty_cart()
    {
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)
            ->post('/orders/checkout');

        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }

    public function test_customer_cannot_checkout_with_pending_order()
    {
        $customer = $this->makeCustomer();
        $this->makeCart($customer);

        // Buat pending order
        Order::factory()->create([
            'user_id' => $customer->id,
            'status'  => 'pending',
        ]);

        $response = $this->actingAs($customer)
            ->post('/orders/checkout');

        $response->assertSessionHas('error');
    }
}
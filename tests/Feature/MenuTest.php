<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    // ======================
    // GUEST
    // ======================

    public function test_guest_can_see_menu_page()
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
    }

    public function test_menu_page_shows_menus()
    {
        Menu::factory()->count(3)->create();

        $response = $this->get('/menu');
        $response->assertStatus(200);
        $response->assertViewHas('menus');
    }

    public function test_guest_cannot_add_to_cart()
    {
        $menu = Menu::factory()->create();

        $response = $this->post("/cart/add/{$menu->id}", [
            'quantity' => 1,
        ]);

        // Redirect ke login
        $response->assertRedirect('/login');
    }

    // ======================
    // CUSTOMER ADD TO CART
    // ======================

    public function test_customer_can_add_menu_to_cart()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $menu     = Menu::factory()->create(['price' => 15000]);

        $response = $this->actingAs($customer)->post("/cart/add/{$menu->id}", [
            'quantity' => 2,
            'notes'    => 'Tanpa gula',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('carts', [
            'user_id' => $customer->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);
    }

    public function test_adding_same_menu_increments_quantity()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $menu     = Menu::factory()->create();

        // Tambah pertama
        $this->actingAs($customer)->post("/cart/add/{$menu->id}", ['quantity' => 1]);

        // Tambah lagi
        $this->actingAs($customer)->post("/cart/add/{$menu->id}", ['quantity' => 2]);

        $cart = Cart::where('user_id', $customer->id)->where('menu_id', $menu->id)->first();
        $this->assertEquals(3, $cart->quantity);
    }

    // ======================
    // ADMIN MENU CRUD
    // ======================

    public function test_admin_can_create_menu()
    {
        Storage::fake('public');
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->post('/admin/menu/store', [
            'name'        => 'Kopi Susu',
            'description' => 'Kopi susu enak',
            'price'       => 15000,
            'image'       => UploadedFile::fake()->image('menu.jpg'),
        ]);

        $response->assertRedirect('/admin/menu');
        $this->assertDatabaseHas('menus', ['name' => 'Kopi Susu']);
    }

    public function test_admin_can_delete_menu()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $menu  = Menu::factory()->create();

        $response = $this->actingAs($owner)->delete("/admin/menu/delete/{$menu->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }
}
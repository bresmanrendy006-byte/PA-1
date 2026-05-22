<?php

namespace Tests\Feature;

use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimoniTest extends TestCase
{
    use RefreshDatabase;

    // ======================
    // INDEX
    // ======================

    public function test_guest_can_see_testimoni_page()
    {
        $response = $this->get('/testimoni');
        $response->assertStatus(200);
    }

    public function test_testimoni_page_shows_testimonis()
    {
        $user = User::factory()->create(['role' => 'customer']);
        Testimoni::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->get('/testimoni');
        $response->assertStatus(200);
        $response->assertViewHas('testimonis');
    }

    // ======================
    // STORE
    // ======================

    public function test_guest_cannot_submit_testimoni()
    {
        $response = $this->post('/testimoni', [
            'isi'    => 'Kopi enak!',
            'rating' => 5,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_customer_can_submit_testimoni()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->post('/testimoni', [
            'isi'    => 'Kopi enak banget!',
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('testimonis', [
            'user_id' => $customer->id,
            'isi'     => 'Kopi enak banget!',
            'rating'  => 5,
        ]);
    }

    public function test_testimoni_requires_isi()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->post('/testimoni', [
            'isi'    => '',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('isi');
    }

    public function test_testimoni_rating_must_be_between_1_and_5()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->post('/testimoni', [
            'isi'    => 'Test',
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    // ======================
    // UPDATE
    // ======================

    public function test_customer_can_update_own_testimoni()
    {
        $customer  = User::factory()->create(['role' => 'customer']);
        $testimoni = Testimoni::factory()->create([
            'user_id' => $customer->id,
            'isi'     => 'Kopi biasa',
            'rating'  => 3,
        ]);

        $response = $this->actingAs($customer)->put("/testimoni/{$testimoni->id}", [
            'isi'    => 'Kopi luar biasa!',
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('testimonis', [
            'id'  => $testimoni->id,
            'isi' => 'Kopi luar biasa!',
        ]);
    }

    public function test_customer_cannot_update_other_users_testimoni()
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);
        $testimoni = Testimoni::factory()->create(['user_id' => $customer1->id]);

        $response = $this->actingAs($customer2)->put("/testimoni/{$testimoni->id}", [
            'isi'    => 'Coba edit',
            'rating' => 1,
        ]);

        $response->assertStatus(403);
    }

    // ======================
    // DESTROY
    // ======================

    public function test_customer_can_delete_own_testimoni()
    {
        $customer  = User::factory()->create(['role' => 'customer']);
        $testimoni = Testimoni::factory()->create(['user_id' => $customer->id]);

        $response = $this->actingAs($customer)
            ->delete("/testimoni/{$testimoni->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('testimonis', ['id' => $testimoni->id]);
    }

    public function test_customer_cannot_delete_other_users_testimoni()
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);
        $testimoni = Testimoni::factory()->create(['user_id' => $customer1->id]);

        $response = $this->actingAs($customer2)
            ->delete("/testimoni/{$testimoni->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('testimonis', ['id' => $testimoni->id]);
    }

    // ======================
    // ADMIN
    // ======================

    public function test_admin_can_delete_any_testimoni()
    {
        $owner     = User::factory()->create(['role' => 'owner']);
        $customer  = User::factory()->create(['role' => 'customer']);
        $testimoni = Testimoni::factory()->create(['user_id' => $customer->id]);

        $response = $this->actingAs($owner)
            ->delete("/admin/testimoni/{$testimoni->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('testimonis', ['id' => $testimoni->id]);
    }
}
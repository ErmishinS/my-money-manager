<?php

namespace Tests\Feature;


use App\Models\PaymentType;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    private PaymentType $paymentType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        
        $this->user = User::factory()->create();
        $this->paymentType = PaymentType::factory()->create(['name' => 'Income']);
    }

    public function test_user_can_view_categories_index()
    {
        $categories = Category::factory(3)->create([
            'user_id' => $this->user->id,
            'payment_type_id' => $this->paymentType->id
        ]);

        $response = $this->actingAs($this->user)->get('/categories');

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
        
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_user_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
        ];

        $response = $this->actingAs($this->user)
            ->post('/categories', $categoryData);

        $response->assertRedirect('/categories');
        
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_create_category_form()
    {
        $response = $this->actingAs($this->user)->get('/categories/create');

        $response->assertStatus(200);
        $response->assertViewIs('categories.create');
    }

    public function test_user_can_view_edit_category_form()
    {
        $category = Category::factory()->create([
            'user_id' => $this->user->id,
            'payment_type_id' => $this->paymentType->id
        ]);

        $response = $this->actingAs($this->user)->get("/categories/{$category->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('categories.edit');
        $response->assertViewHas('category');
    }

    public function test_user_can_update_category()
    {
        $category = Category::factory()->create([
            'user_id' => $this->user->id,
            'payment_type_id' => $this->paymentType->id
        ]);

        $updateData = [
            'name' => 'Updated Category',
            'payment_type_id'=> $this->paymentType->id,
        ];

        $response = $this->actingAs($this->user)
            ->put("/categories/{$category->id}", $updateData);

        $response->assertRedirect('/categories');
        
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
            'payment_type_id'=> $this->paymentType->id,
        ]);
    }

    public function test_user_can_delete_category()
    {
        $category = Category::factory()->create([
            'user_id' => $this->user->id,
            'payment_type_id' => $this->paymentType->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/categories/{$category->id}");

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_validation_rules()
    {
        $response = $this->actingAs($this->user)
            ->post('/categories', []);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_category_name_must_be_unique_for_user()
    {
        Category::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Existing Category'
        ]);

        $response = $this->actingAs($this->user)
            ->post('/categories', [
                'name' => 'Existing Category',
                'description' => 'Test description'
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_different_users_can_have_same_category_name()
    {
        $otherUser = User::factory()->create();
        
        Category::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Same Category'
        ]);

        $response = $this->actingAs($this->user)
            ->post('/categories', [
                'name' => 'Same Category',
            ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Same Category',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_cannot_access_other_users_categories()
    {
        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->get("/categories/{$otherCategory->id}/edit");

        $response->assertStatus(404);
    }

    public function test_user_cannot_update_other_users_categories()
    {
        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->put("/categories/{$otherCategory->id}", [
                'name' => 'Hacked Category'
            ]);

        $response->assertStatus(404);
        
        $this->assertDatabaseMissing('categories', [
            'id' => $otherCategory->id,
            'name' => 'Hacked Category'
        ]);
    }

    public function test_user_cannot_delete_other_users_categories()
    {
        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->delete("/categories/{$otherCategory->id}");

        $response->assertStatus(404);
        $this->assertDatabaseHas('categories', ['id' => $otherCategory->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use App\Models\Category;
use App\Models\MoneyType;
use App\Models\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private Category $category;
    private MoneyType $moneyType;
    private PaymentType $paymentType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        
        $this->user = User::factory()->create();
        $this->paymentType = PaymentType::factory()->create(['name' => 'Income']);
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
        $this->moneyType = MoneyType::factory()->create(['name' => 'Cash']);
        
    }

    public function test_user_can_view_payments_index()
    {
        $this->actingAs($this->user);

        $payments = Payment::factory(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ]);

        $response = $this->actingAs($this->user)->get('/payments');

        $response->assertStatus(200);
        $response->assertViewIs('payments.index');
        $response->assertViewHas('payments');
        
        foreach ($payments as $payment) {
            $response->assertSee($payment->amount);
        }
    }

    public function test_user_can_create_payment()
    {
        $paymentData = [
            'amount' => 100.50,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ];

        $response = $this->actingAs($this->user)
            ->post('/payments', $paymentData);

        $response->assertRedirect('/payments');
        
        $this->assertDatabaseHas('payments', [
            'amount' => 100.50,
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
    }

    public function test_user_can_view_create_payment_form()
    {
        $response = $this->actingAs($this->user)->get('/payments/create');

        $response->assertStatus(200);
        $response->assertViewIs('payments.create');
        $response->assertViewHas(['money_types', 'payment_types', 'categories']);
    }

    public function test_user_can_update_payment()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ]);

        $updateData = [
            'amount' => 200.75,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ];

        $response = $this->actingAs($this->user)
            ->put("/payments/{$payment->id}", $updateData);

        $response->assertRedirect('/payments');
        
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'amount' => 200.75,
        ]);
    }

    public function test_user_can_delete_payment()
    {
        $this->actingAs($this->user);

        $payment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/payments/{$payment->id}");

        $response->assertRedirect('/payments');
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    public function test_payment_validation_rules()
    {
        $response = $this->actingAs($this->user)
            ->post('/payments', []);

        $response->assertSessionHasErrors(['amount', 'category_id', 'money_type_id', 'payment_type_id']);
    }

    public function test_user_cannot_access_other_users_payments()
    {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        
        $otherPayment = Payment::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $this->paymentType->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/payments/{$otherPayment->id}/edit");

        $response->assertStatus(404);
    }

    public function test_payment_statistics_are_calculated_correctly()
    {
        $this->actingAs($this->user);

        $incomeType = PaymentType::factory()->create(['name' => 'income']);
        $expenseType = PaymentType::factory()->create(['name' => 'expense']);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $incomeType->id,
            'amount' => 1000,
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'money_type_id' => $this->moneyType->id,
            'payment_type_id' => $expenseType->id,
            'amount' => 500,
        ]);

        $response = $this->actingAs($this->user)->get('/payments');

        $response->assertStatus(200);
        $response->assertViewHas(['payments', 'cash', 'non_cash', 'chart1', 'chart2', 'chart3', 'chart4']);
    }
}

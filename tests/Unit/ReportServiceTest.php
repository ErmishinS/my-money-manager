<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\Category;
use App\Models\MoneyType;
use App\Models\PaymentType;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReportService $reportService;
    private User $user;
    private Category $category1;
    private Category $category2;
    private MoneyType $cashType;
    private MoneyType $cardType;
    private PaymentType $incomeType;
    private PaymentType $expenseType;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->reportService = new ReportService();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->paymentType = PaymentType::factory()->create(['name' => 'Income']);
        
        
        $this->category1 = Category::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Food'
        ]);
        
        $this->category2 = Category::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Transport'
        ]);
        
        $this->cashType = MoneyType::factory()->create(['name' => 'Cash']);
        
        $this->cardType = MoneyType::factory()->create(['name' => 'Non-cash']);
        
        $this->incomeType = PaymentType::factory()->create(['name' => 'Income']);
        
        $this->expenseType = PaymentType::factory()->create(['name' => 'Expense']);
    }

    public function test_get_chart1_returns_expenses_by_category_chart()
    {
        $this->actingAs($this->user);

        $chart = $this->reportService->getChart1();

        $this->assertInstanceOf(LaravelChart::class, $chart);
    }

    public function test_get_chart1_has_correct_configuration()
    {
        $chart = $this->reportService->getChart1();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $this->assertEquals('Expenses by category', $chartOptions['chart_title']);
        $this->assertEquals('group_by_relationship', $chartOptions['report_type']);
        $this->assertEquals('category', $chartOptions['relationship_name']);
        $this->assertEquals('App\Models\Payment', $chartOptions['model']);
        $this->assertEquals('payment_type_id = 2', $chartOptions['where_raw']);
        $this->assertEquals('name', $chartOptions['group_by_field']);
        $this->assertEquals('created_at', $chartOptions['filter_field']);
        $this->assertEquals(30, $chartOptions['filter_days']);
        $this->assertEquals('day', $chartOptions['group_by_period']);
        $this->assertEquals('sum', $chartOptions['aggregate_function']);
        $this->assertEquals('amount', $chartOptions['aggregate_field']);
        $this->assertEquals('pie', $chartOptions['chart_type']);
        $this->assertEquals('300px', $chartOptions['chart_height']);
        $this->assertIsCallable($chartOptions['aggregate_transform']);
    }

    public function test_get_chart1_aggregate_transform_function()
    {
        $chart = $this->reportService->getChart1();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $transformFunction = $chartOptions['aggregate_transform'];
        $result = $transformFunction(100);
        
        $this->assertEquals(-100, $result);
    }

    public function test_get_chart2_returns_incomes_by_category_chart()
    {
        $chart = $this->reportService->getChart2();

        $this->assertInstanceOf(LaravelChart::class, $chart);
    }

    public function test_get_chart2_has_correct_configuration()
    {
        $chart = $this->reportService->getChart2();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $this->assertEquals('Incomes by category', $chartOptions['chart_title']);
        $this->assertEquals('group_by_relationship', $chartOptions['report_type']);
        $this->assertEquals('category', $chartOptions['relationship_name']);
        $this->assertEquals('App\Models\Payment', $chartOptions['model']);
        $this->assertEquals('payment_type_id = 1', $chartOptions['where_raw']);
        $this->assertEquals('name', $chartOptions['group_by_field']);
        $this->assertEquals('created_at', $chartOptions['filter_field']);
        $this->assertEquals(30, $chartOptions['filter_days']);
        $this->assertEquals('day', $chartOptions['group_by_period']);
        $this->assertEquals('sum', $chartOptions['aggregate_function']);
        $this->assertEquals('amount', $chartOptions['aggregate_field']);
        $this->assertEquals('pie', $chartOptions['chart_type']);
        $this->assertArrayNotHasKey('aggregate_transform', $chartOptions);
    }

    public function test_get_chart3_returns_incomes_by_dates_chart()
    {
        $chart = $this->reportService->getChart3();

        $this->assertInstanceOf(LaravelChart::class, $chart);
    }

    public function test_get_chart3_has_correct_configuration()
    {
        $chart = $this->reportService->getChart3();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $this->assertEquals('Incomes by dates', $chartOptions['chart_title']);
        $this->assertEquals('group_by_date', $chartOptions['report_type']);
        $this->assertEquals('App\Models\Payment', $chartOptions['model']);
        $this->assertEquals('payment_type_id = 1', $chartOptions['where_raw']);
        $this->assertEquals('created_at', $chartOptions['group_by_field']);
        $this->assertEquals('created_at', $chartOptions['filter_field']);
        $this->assertEquals(30, $chartOptions['filter_days']);
        $this->assertEquals('day', $chartOptions['group_by_period']);
        $this->assertEquals('sum', $chartOptions['aggregate_function']);
        $this->assertEquals('amount', $chartOptions['aggregate_field']);
        $this->assertEquals('line', $chartOptions['chart_type']);
    }

    public function test_get_chart4_returns_expenses_by_dates_chart()
    {
        $chart = $this->reportService->getChart4();

        $this->assertInstanceOf(LaravelChart::class, $chart);
    }

    public function test_get_chart4_has_correct_configuration()
    {
        $chart = $this->reportService->getChart4();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $this->assertEquals('Expenses by dates', $chartOptions['chart_title']);
        $this->assertEquals('group_by_date', $chartOptions['report_type']);
        $this->assertEquals('App\Models\Payment', $chartOptions['model']);
        $this->assertEquals('payment_type_id = 2', $chartOptions['where_raw']);
        $this->assertEquals('created_at', $chartOptions['group_by_field']);
        $this->assertEquals('created_at', $chartOptions['filter_field']);
        $this->assertEquals(30, $chartOptions['filter_days']);
        $this->assertEquals('day', $chartOptions['group_by_period']);
        $this->assertEquals('sum', $chartOptions['aggregate_function']);
        $this->assertEquals('amount', $chartOptions['aggregate_field']);
        $this->assertEquals('line', $chartOptions['chart_type']);
        $this->assertIsCallable($chartOptions['aggregate_transform']);
    }

    public function test_get_chart4_aggregate_transform_function()
    {
        $chart = $this->reportService->getChart4();
        $reflection = new \ReflectionClass($chart);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);
        $chartOptions = $options->getValue($chart);

        $transformFunction = $chartOptions['aggregate_transform'];
        $result = $transformFunction(250);
        
        $this->assertEquals(-250, $result);
    }

    public function test_get_payments_returns_paginated_payments()
    {
        // Create test payments
        Payment::factory(15)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => $this->cashType->id,
            'payment_type_id' => $this->expenseType->id,
        ]);

        $result = $this->reportService->getPayments();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
        $this->assertCount(10, $result->items());
    }

    public function test_get_payments_includes_relationships()
    {
        $payment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => $this->cashType->id,
            'payment_type_id' => $this->expenseType->id,
        ]);

        $result = $this->reportService->getPayments();
        $firstPayment = $result->items()[0];

        $this->assertTrue($firstPayment->relationLoaded('money_type'));
        $this->assertTrue($firstPayment->relationLoaded('payment_type'));
        $this->assertTrue($firstPayment->relationLoaded('category'));
    }

    public function test_get_payments_ordered_by_created_at_desc()
    {
        $oldPayment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => $this->cashType->id,
            'payment_type_id' => $this->expenseType->id,
            'created_at' => now()->subDays(2),
        ]);

        $newPayment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => $this->cashType->id,
            'payment_type_id' => $this->expenseType->id,
            'created_at' => now(),
        ]);

        $result = $this->reportService->getPayments();
        $payments = $result->items();

        $this->assertEquals($newPayment->id, $payments[0]->id);
        $this->assertEquals($oldPayment->id, $payments[1]->id);
    }

    public function test_get_cash_amount_returns_correct_sum()
    {
        // Create cash payments
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->incomeType->id,
            'amount' => 100.00,
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->expenseType->id,
            'amount' => -50.00,
        ]);

        // Create non-cash payment (should not be included)
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 2, // Card
            'payment_type_id' => $this->incomeType->id,
            'amount' => 200.00,
        ]);

        $result = $this->reportService->getCashAmount();

        $this->assertEquals(50.00, $result);
    }

    public function test_get_cash_amount_returns_zero_when_no_cash_payments()
    {
        // Create only non-cash payments
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 2, // Card
            'payment_type_id' => $this->incomeType->id,
            'amount' => 100.00,
        ]);

        $result = $this->reportService->getCashAmount();

        $this->assertEquals(0, $result);
    }

    public function test_get_non_cash_amount_returns_correct_sum()
    {
        // Create non-cash payments
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 2, // Card
            'payment_type_id' => $this->incomeType->id,
            'amount' => 300.00,
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 2, // Card
            'payment_type_id' => $this->expenseType->id,
            'amount' => -100.00,
        ]);

        // Create cash payment (should not be included)
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->incomeType->id,
            'amount' => 150.00,
        ]);

        $result = $this->reportService->getNonCashAmount();

        $this->assertEquals(200.00, $result);
    }

    public function test_get_non_cash_amount_returns_zero_when_no_non_cash_payments()
    {
        // Create only cash payments
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->incomeType->id,
            'amount' => 100.00,
        ]);

        $result = $this->reportService->getNonCashAmount();

        $this->assertEquals(0, $result);
    }

    public function test_get_payments_with_empty_database()
    {
        $result = $this->reportService->getPayments();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    public function test_all_chart_methods_return_laravel_chart_instance()
    {
        $charts = [
            $this->reportService->getChart1(),
            $this->reportService->getChart2(),
            $this->reportService->getChart3(),
            $this->reportService->getChart4(),
        ];

        foreach ($charts as $chart) {
            $this->assertInstanceOf(LaravelChart::class, $chart);
        }
    }

    public function test_cash_and_non_cash_amounts_with_mixed_payments()
    {
        // Create mixed payments
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->incomeType->id,
            'amount' => 500.00,
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 2, // Card
            'payment_type_id' => $this->incomeType->id,
            'amount' => 1000.00,
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category1->id,
            'money_type_id' => 1, // Cash
            'payment_type_id' => $this->expenseType->id,
            'amount' => -200.00,
        ]);

        $cashAmount = $this->reportService->getCashAmount();
        $nonCashAmount = $this->reportService->getNonCashAmount();

        $this->assertEquals(300.00, $cashAmount); // 500 - 200
        $this->assertEquals(1000.00, $nonCashAmount);
    }
}
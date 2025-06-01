<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Category;
use App\Models\MoneyType;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportService = new ReportService();

        $payments = $reportService->getPayments();
        $cash = $reportService->getCashAmount();
        $non_cash = $reportService->getNonCashAmount();

        $chart1 = $reportService->getChart1();
        $chart2 = $reportService->getChart2();
        $chart3 = $reportService->getChart3();
        $chart4 = $reportService->getChart4();

        return view('payments.index', compact('payments', 'cash', 'non_cash', 'chart1', 'chart2', 'chart3', 'chart4'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $money_types = MoneyType::select('name', 'id')->get();
        $payment_types = PaymentType::select('name', 'id')->get();
        $categories = Category::where('user_id', Auth::id())->get();

        return view('payments.create', compact('money_types', 'payment_types', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();

        if ($data['payment_type_id'] == PaymentType::where('name', 'Expense')->first()?->id) {
            $data['amount'] = -abs($data['amount']);
        } else {
            $data['amount'] = abs($data['amount']);
        }

        $payment = Payment::create([
            'amount' => $data['amount'],
            'user_id' => Auth::id(),
            'payment_type_id' => $data['payment_type_id'],
            'money_type_id' => $data['money_type_id'],
            'category_id' => $data['category_id'],
        ]);

        return redirect()->route('payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        abort_if($payment->user->id != auth()->id(), 404);

        $money_types = MoneyType::select('name', 'id')->get();
        $payment_types = PaymentType::select('name', 'id')->get();
        $categories = Category::where('user_id', Auth::id())->get();

        return view('payments.edit', compact('money_types', 'payment_types', 'categories', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $data = $request->validated();

        if ($data['payment_type_id'] == PaymentType::where('name', 'Expense')->first()?->id) {
            $data['amount'] = -abs($data['amount']);
        } else {
            $data['amount'] = abs($data['amount']);
        }

        $payment->update([
            'amount' => $data['amount'],
            'user_id' => Auth::id(),
            'payment_type_id' => $data['payment_type_id'],
            'money_type_id' => $data['money_type_id'],
            'category_id' => $data['category_id'],
        ]);

        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index');
    }
}

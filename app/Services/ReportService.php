<?php

namespace App\Services;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Models\Payment;

class ReportService {
    public function getChart1() {
        $chart_options = [
            'chart_title' => 'Expenses by category',
            'report_type' => 'group_by_relationship',
            'relationship_name' => 'category',
            'model' => 'App\Models\Payment',
            'where_raw' => 'payment_type_id = 2',
            'group_by_field' => 'name',
            'filter_field' => 'created_at',
            'filter_days' => 30,
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_height' => '300px',
            'aggregate_transform' => function($value) {
                return -$value;
            },
            'chart_type' => 'pie',
        ];

        return new LaravelChart($chart_options);
    }

    public function getChart2() {
        $chart_options = [
            'chart_title' => 'Incomes by category',
            'report_type' => 'group_by_relationship',
            'relationship_name' => 'category',
            'model' => 'App\Models\Payment',
            'where_raw' => 'payment_type_id = 1',
            'group_by_field' => 'name',
            'filter_field' => 'created_at',
            'filter_days' => 30,
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'pie',
        ];

        return new LaravelChart($chart_options);
    }

    public function getChart3() {
        $chart_options = [
            'chart_title' => 'Incomes by dates',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Payment',
            'where_raw' => 'payment_type_id = 1',
            'group_by_field' => 'created_at',
            'filter_field' => 'created_at',
            'filter_days' => 30,
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'line',
        ];

        return new LaravelChart($chart_options);
    }

    public function getChart4() {
        $chart_options = [
            'chart_title' => 'Expenses by dates',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Payment',
            'where_raw' => 'payment_type_id = 2',
            'group_by_field' => 'created_at',
            'filter_field' => 'created_at',
            'filter_days' => 30,
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'aggregate_transform' => function($value) {
                return -$value;
            },
            'chart_type' => 'line',
        ];

        return new LaravelChart($chart_options);
    }

    public function getPayments() {
        return Payment::with(['money_type', 'payment_type', 'category'])->orderByDesc('created_at')->paginate(10);
    }

    public function getCashAmount() {
        return Payment::where('money_type_id', 1)->sum('amount');
    }

    public function getNonCashAmount() {
        return Payment::where('money_type_id', 2)->sum('amount');
    }
}
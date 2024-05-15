<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\MoneyType;
use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payment_type = PaymentType::all()->random()->id;
        $amount = (rand(1, 5000) / 10);
        if ($payment_type == 2) {
            $amount = -$amount;
        }

        return [
            'amount' => $amount,
            'money_type_id' => MoneyType::all()->random()->id,
            'payment_type_id' => $payment_type,
            'user_id' => User::all()->first(),
            'category_id' => Category::all()->random()->id,
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}

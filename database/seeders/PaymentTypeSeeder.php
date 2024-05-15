<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('payment_types')->delete();

        \DB::table('payment_types')->insert(array (
            0 =>
                array (
                    'created_at' => '2024-05-14 13:50:21',
                    'id' => 1,
                    'name' => 'Income',
                    'updated_at' => '2024-05-14 13:50:21',
                ),
            1 =>
                array (
                    'created_at' => '2024-05-14 13:50:21',
                    'id' => 2,
                    'name' => 'Expense',
                    'updated_at' => '2024-05-14 13:50:21',
                ),
        ));
    }
}

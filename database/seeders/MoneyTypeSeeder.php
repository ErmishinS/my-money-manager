<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoneyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('money_types')->delete();

        \DB::table('money_types')->insert(array (
            0 =>
                array (
                    'created_at' => '2024-05-14 13:50:21',
                    'id' => 1,
                    'name' => 'Cash',
                    'updated_at' => '2024-05-14 13:50:21',
                ),
            1 =>
                array (
                    'created_at' => '2024-05-14 13:50:21',
                    'id' => 2,
                    'name' => 'Non-cash',
                    'updated_at' => '2024-05-14 13:50:21',
                ),
        ));
    }
}

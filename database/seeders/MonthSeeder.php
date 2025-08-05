<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $months = [
            ['name' => 'January', 'short_name' => 'Jan', 'number' => 1],
            ['name' => 'February', 'short_name' => 'Feb', 'number' => 2],
            ['name' => 'March', 'short_name' => 'Mar', 'number' => 3],
            ['name' => 'April', 'short_name' => 'Apr', 'number' => 4],
            ['name' => 'May', 'short_name' => 'May', 'number' => 5],
            ['name' => 'June', 'short_name' => 'Jun', 'number' => 6],
            ['name' => 'July', 'short_name' => 'Jul', 'number' => 7],
            ['name' => 'August', 'short_name' => 'Aug', 'number' => 8],
            ['name' => 'September', 'short_name' => 'Sep', 'number' => 9],
            ['name' => 'October', 'short_name' => 'Oct', 'number' => 10],
            ['name' => 'November', 'short_name' => 'Nov', 'number' => 11],
            ['name' => 'December', 'short_name' => 'Dec', 'number' => 12],
        ];

        DB::table('month_names')->insert($months);
    }
}

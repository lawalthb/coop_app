<?php

namespace Database\Seeders;

use App\Models\Year;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    public function run()
    {
        for ($year = 2022; $year <= 2050; $year++) {
            Year::create(['year' => $year]);
        }
    }
}

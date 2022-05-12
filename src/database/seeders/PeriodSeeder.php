<?php

namespace Database\Seeders;

use App\Models\HoursPeriod;
use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Period::firstOrCreate(["code" => "P1", "name" => "Hora punta"]);
        Period::firstOrCreate(["code" => "P2", "name" => "Hora llana"]);
        Period::firstOrCreate(["code" => "P3", "name" => "Hora valle"]);

    }
}

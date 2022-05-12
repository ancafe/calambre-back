<?php

namespace Database\Seeders;

use App\Models\Hour;
use Illuminate\Database\Seeder;

class HoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hour::firstOrCreate(["id" => 1, "name" => "00 - 01 h"]);
        Hour::firstOrCreate(["id" => 2, "name" => "01 - 02 h"]);
        Hour::firstOrCreate(["id" => 3, "name" => "02 - 03 h"]);
        Hour::firstOrCreate(["id" => 4, "name" => "03 - 04 h"]);
        Hour::firstOrCreate(["id" => 5, "name" => "04 - 05 h"]);
        Hour::firstOrCreate(["id" => 6, "name" => "05 - 06 h"]);
        Hour::firstOrCreate(["id" => 7, "name" => "06 - 07 h"]);
        Hour::firstOrCreate(["id" => 8, "name" => "07 - 08 h"]);
        Hour::firstOrCreate(["id" => 9, "name" => "08 - 09 h"]);
        Hour::firstOrCreate(["id" => 10, "name" => "09 - 10 h"]);
        Hour::firstOrCreate(["id" => 11, "name" => "10 - 11 h"]);
        Hour::firstOrCreate(["id" => 12, "name" => "11 - 12 h"]);
        Hour::firstOrCreate(["id" => 13, "name" => "12 - 13 h"]);
        Hour::firstOrCreate(["id" => 14, "name" => "13 - 14 h"]);
        Hour::firstOrCreate(["id" => 15, "name" => "14 - 15 h"]);
        Hour::firstOrCreate(["id" => 16, "name" => "15 - 16 h"]);
        Hour::firstOrCreate(["id" => 17, "name" => "16 - 17 h"]);
        Hour::firstOrCreate(["id" => 18, "name" => "17 - 18 h"]);
        Hour::firstOrCreate(["id" => 19, "name" => "18 - 19 h"]);
        Hour::firstOrCreate(["id" => 20, "name" => "19 - 20 h"]);
        Hour::firstOrCreate(["id" => 21, "name" => "20 - 21 h"]);
        Hour::firstOrCreate(["id" => 22, "name" => "21 - 22 h"]);
        Hour::firstOrCreate(["id" => 23, "name" => "22 - 23 h"]);
        Hour::firstOrCreate(["id" => 24, "name" => "23 - 24 h"]);


    }
}

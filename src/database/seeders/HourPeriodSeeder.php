<?php

namespace Database\Seeders;

use App\Models\HourPeriod;
use Illuminate\Database\Seeder;

class HourPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //regular day

        //weekend
        foreach ([0, 6] as $weekday) {
            for ($i = 1; $i <= 24; $i++) {
                HourPeriod::firstOrCreate(["period_code" => "P3", "hour_id" => $i, "weekday" => $weekday, "holiday" => false]);
            }
        }

        // workday
        for ($weekday = 1; $weekday <= 5; $weekday++) {
            foreach ([1, 2, 3, 4, 5, 6, 7, 8] as $i) {
                HourPeriod::firstOrCreate(["period_code" => "P3", "hour_id" => $i, "weekday" => $weekday, "holiday" => false]);
            }

            foreach ([9, 10, 15, 16, 17, 18, 23, 24] as $i) {
                HourPeriod::firstOrCreate(["period_code" => "P2", "hour_id" => $i, "weekday" => $weekday, "holiday" => false]);
            }

            foreach ([11, 12, 13, 14, 19, 20, 21, 22] as $i) {
                HourPeriod::firstOrCreate(["period_code" => "P1", "hour_id" => $i, "weekday" => $weekday, "holiday" => false]);
            }

        }


        //public holidays
        //all days and all hours
        foreach ([0, 1, 2, 3, 4, 5, 6] as $weekday) {
            for ($i = 1; $i <= 24; $i++) {
                HourPeriod::firstOrCreate(["period_code" => "P3", "hour_id" => $i, "weekday" => $weekday, "holiday" => true]);
            }
        }

    }
}

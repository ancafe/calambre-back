<?php

namespace Database\Seeders;

use App\Models\PublicHoliday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            HoursSeeder::class,
            PeriodSeeder::class,
            HourPeriodSeeder::class,
            PublicHolidaySeeder::class
        ]);
    }
}

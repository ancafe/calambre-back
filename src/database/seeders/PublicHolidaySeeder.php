<?php

namespace Database\Seeders;

use App\Models\PublicHoliday;
use Illuminate\Database\Seeder;

class PublicHolidaySeeder extends Seeder
{

    public function run()
    {
        PublicHoliday::firstOrCreate(["date" => "2022-01-01", "name" => "Año Nuevo"]);
        PublicHoliday::firstOrCreate(["date" => "2022-01-06", "name" => "Epifanía del Señor"]);
        PublicHoliday::firstOrCreate(["date" => "2022-06-15", "name" => "Asunción de la Vírgen"]);
        PublicHoliday::firstOrCreate(["date" => "2022-10-12", "name" => "Fiesta Nacional de España"]);
        PublicHoliday::firstOrCreate(["date" => "2022-11-01", "name" => "Todos los Santos"]);
        PublicHoliday::firstOrCreate(["date" => "2022-12-06", "name" => "Día de la Constitución Española"]);
        PublicHoliday::firstOrCreate(["date" => "2022-12-08", "name" => "Inmaculada Concepción"]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\TimeZone;
use DateTimeZone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        foreach ($timezones as $timezone) {
            // Dividir el timezone en continente y zona (ej: America/New_York)
            $parts = explode('/', $timezone);
            $continent = $parts[0] ?? 'Unknown';
            $zone = $parts[1] ?? $timezone;

            TimeZone::create([
                'time_zone' => $timezone,
                'continent' => $continent,
                'zone' => str_replace('_', ' ', $zone), // Reemplazar guiones bajos por espacios
                'use' => true, // Por defecto, activo
            ]);
        }
    }
}

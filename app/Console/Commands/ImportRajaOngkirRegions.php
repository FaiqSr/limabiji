<?php

namespace App\Console\Commands;

use App\Services\RajaOngkirService;
use Illuminate\Console\Command;

class ImportRajaOngkirRegions extends Command
{
    protected $signature = 'rajaongkir:import-regions';

    protected $description = 'Pre-warm the RajaOngkir region data into the database (provinces, cities, districts). Use when API quota is available.';

    public function handle(RajaOngkirService $rajaOngkir): int
    {
        $this->info('Fetching provinces...');
        $provinces = $rajaOngkir->provinces();

        if (empty($provinces)) {
            $this->error('No provinces returned. Check RAJAONGKIR_API_KEY and quota.');

            return self::FAILURE;
        }

        $this->info('Fetched '.count($provinces).' provinces.');

        $cityCount = 0;
        $districtCount = 0;

        foreach ($provinces as $province) {
            $provinceId = (int) ($province['id'] ?? 0);
            if ($provinceId <= 0) {
                continue;
            }

            $cities = $rajaOngkir->cities($provinceId);
            $this->line("  province {$provinceId}: ".count($cities).' cities');

            foreach ($cities as $city) {
                $cityId = (int) ($city['id'] ?? 0);
                if ($cityId <= 0) {
                    continue;
                }

                $districts = $rajaOngkir->districts($cityId);
                $districtCount += count($districts);
                $cityCount++;
            }
        }

        $this->info("Done. Cached {$cityCount} cities and {$districtCount} districts.");

        return self::SUCCESS;
    }
}

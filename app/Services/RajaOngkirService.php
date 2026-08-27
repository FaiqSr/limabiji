<?php

namespace App\Services;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Composer\CaBundle\CaBundle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected int $originDistrict;

    protected string $couriers;

    public function __construct()
    {
        $this->apiKey = (string) config('rajaongkir.api_key', '');
        $this->baseUrl = rtrim((string) config('rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $this->originDistrict = (int) config('rajaongkir.origin_district', 1391);
        $this->couriers = $this->normalizeCouriers((string) config('rajaongkir.couriers', 'jne:sicepat:jnt:ninja:tiki:anteraja:pos'));
    }

    /**
     * Accept both comma- and colon-separated courier lists and always emit the
     * colon-separated form expected by the komerce /cost endpoint.
     */
    protected function normalizeCouriers(string $couriers): string
    {
        $codes = preg_split('/[\s,:]+/', $couriers, -1, PREG_SPLIT_NO_EMPTY);

        return implode(':', array_map('strtolower', $codes));
    }

    /**
     * CA bundle path used to verify RajaOngkir SSL certificates.
     */
    protected function verifyOption(): bool|string
    {
        if (class_exists(CaBundle::class)) {
            try {
                return CaBundle::getSystemCaRootBundlePath();
            } catch (\Throwable $e) {
                // fall through to default
            }
        }

        return true;
    }

    protected function shouldCacheCosts(): bool
    {
        return (bool) config('rajaongkir.use_cache', true);
    }

    protected function authHeaders(): array
    {
        return [
            'Key' => $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    /**
     * GET a RajaOngkir endpoint and return the `data` payload.
     * Returns [] on HTTP error / upstream error; it never throws.
     */
    protected function get(string $path): array
    {
        try {
            $response = Http::withOptions(['verify' => $this->verifyOption()])
                ->withHeaders($this->authHeaders())
                ->get($this->baseUrl.$path);

            if ($response->successful() && ($response->json('meta.status') ?? '') === 'success') {
                return $response->json('data') ?? [];
            }

            Log::warning("RajaOngkir GET {$path} failed: HTTP {$response->status()} {$response->body()}");
        } catch (\Throwable $e) {
            Log::error("RajaOngkir GET {$path} exception: {$e->getMessage()}");
        }

        return [];
    }

    /**
     * POST the shipping cost calculation. Returns the `data` array or [].
     */
    protected function postCost(int $destinationDistrictId, int $weightGrams): array
    {
        try {
            $response = Http::withOptions(['verify' => $this->verifyOption()])
                ->withHeaders($this->authHeaders())
                ->asForm()
                ->post($this->baseUrl.'/calculate/district/domestic-cost', [
                    'origin' => $this->originDistrict,
                    'destination' => $destinationDistrictId,
                    'weight' => $weightGrams,
                    'courier' => $this->couriers,
                    'price' => 'lowest',
                ]);

            if ($response->successful() && ($response->json('meta.status') ?? '') === 'success') {
                return $response->json('data') ?? [];
            }

            Log::warning("RajaOngkir cost (dest {$destinationDistrictId}, {$weightGrams}g) failed: HTTP {$response->status()} {$response->body()}");
        } catch (\Throwable $e) {
            Log::error("RajaOngkir cost exception (dest {$destinationDistrictId}): {$e->getMessage()}");
        }

        return [];
    }

    /**
     * Return a cached cost result if present, otherwise fetch and cache (only
     * non-empty responses are stored, so transient upstream errors are retried
     * on the next request instead of poisoning the cache).
     */
    protected function cachedCost(string $key, int $ttl, callable $fetch): array
    {
        if (! $this->shouldCacheCosts()) {
            return $fetch();
        }

        $value = Cache::get($key);
        if (is_array($value)) {
            return $value;
        }

        $data = $fetch();
        if ($data !== []) {
            Cache::put($key, $data, $ttl);
        }

        return $data;
    }

    /**
     * Provinces are persisted to the DB. If none are stored yet, hit the
     * endpoint once and persist; every later call reads from the DB.
     */
    public function provinces(): array
    {
        $existing = Province::orderBy('name')->get(['id', 'name']);
        if ($existing->isNotEmpty()) {
            return $existing->toArray();
        }

        foreach ($this->get('/destination/province') as $row) {
            Province::updateOrCreate(
                ['id' => (int) $row['id']],
                ['name' => $row['name'], 'name_lower' => $this->fold($row['name'])]
            );
        }

        return Province::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function cities(int $provinceId): array
    {
        $existing = City::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name', 'province_id', 'zip_code']);
        if ($existing->isEmpty()) {
            foreach ($this->get("/destination/city/{$provinceId}") as $row) {
                City::updateOrCreate(
                    ['id' => (int) $row['id']],
                    [
                        'province_id' => $provinceId,
                        'name' => $row['name'],
                        'name_lower' => $this->fold($row['name']),
                        'zip_code' => $row['zip_code'] ?? null,
                    ]
                );
            }
            $existing = City::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name', 'province_id', 'zip_code']);
        }

        return $existing->toArray();
    }

    public function districts(int $cityId): array
    {
        $existing = District::where('city_id', $cityId)->orderBy('name')->get(['id', 'name', 'city_id', 'zip_code']);
        if ($existing->isEmpty()) {
            foreach ($this->get("/destination/district/{$cityId}") as $row) {
                District::updateOrCreate(
                    ['id' => (int) $row['id']],
                    [
                        'city_id' => $cityId,
                        'name' => $row['name'],
                        'name_lower' => $this->fold($row['name']),
                        'zip_code' => $row['zip_code'] ?? null,
                    ]
                );
            }
            $existing = District::where('city_id', $cityId)->orderBy('name')->get(['id', 'name', 'city_id', 'zip_code']);
        }

        return $existing->toArray();
    }

    /**
     * Returns the parsed cost response (list of courier services with cost & etd).
     * Cost results are cached for a short TTL (shipping prices change over time).
     */
    public function domesticCost(int $destinationDistrictId, int $weightGrams): array
    {
        return $this->cachedCost(
            "rajaongkir.cost.{$destinationDistrictId}.{$weightGrams}",
            (int) config('rajaongkir.cost_cache_ttl', 600),
            fn () => $this->postCost($destinationDistrictId, $weightGrams)
        );
    }

    /**
     * Forget cost caches. Region data lives in the DB and no longer needs clearing.
     */
    public function clearCostCache(): void
    {
        Cache::flush();
    }

    /**
     * Lowercase, trim, and collapse whitespace so region names compare cleanly.
     */
    protected function fold(string $name): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $name)));
    }
}

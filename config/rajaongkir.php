<?php

return [

    'api_key' => env('RAJAONGKIR_API_KEY', ''),

    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),

    /*
    |--------------------------------------------------------------------------
    | Origin / store shipping location
    |--------------------------------------------------------------------------
    | The `origin` for shipping cost calculation is the RajaOngkir *district* id
    | where the shop ships from (e.g. roastery/gudang).
    |--------------------------------------------------------------------------
    */
    'origin_district' => env('RAJAONGKIR_ORIGIN_DISTRICT', 1391),

    /*
    |--------------------------------------------------------------------------
    | Couriers queried on the cost endpoint
    |--------------------------------------------------------------------------
    | Colon-separated list of RajaOngkir courier codes.
    |--------------------------------------------------------------------------
    */
    'couriers' => env('RAJAONGKIR_COURIERS', 'jne:sicepat:jnt:ninja:tiki:anteraja:pos'),

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    | Region data (province/city/district) is static and is cached forever (only a
    | single upstream request per region id over the cache lifetime). Shipping
    | cost results change over time and are cached for `cost_cache_ttl` seconds
    | so a checkout batch does not re-hit the API each time.
    |--------------------------------------------------------------------------
    */
    'use_cache' => env('RAJAONGKIR_USE_CACHE', true),

    'cost_cache_ttl' => (int) env('RAJAONGKIR_COST_CACHE_TTL', 600),

];

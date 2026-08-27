<?php

namespace Tests\Feature\Store;

use App\Models\City;
use App\Models\District;
use App\Models\Product;
use App\Models\Province;
use App\Services\CartService;
use App\Services\RajaOngkirService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RajaOngkirTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    private function fakeRajaOngkir(): void
    {
        Http::fake([
            '*/destination/province' => Http::response([
                'meta' => ['message' => 'ok', 'code' => 200, 'status' => 'success'],
                'data' => [['id' => 6, 'name' => 'JAWA BARAT'], ['id' => 11, 'name' => 'DKI JAKARTA']],
            ]),
            '*/destination/city/*' => Http::response([
                'meta' => ['message' => 'ok', 'code' => 200, 'status' => 'success'],
                'data' => [['id' => 455, 'name' => 'BOGOR'], ['id' => 501, 'name' => 'DEPOK']],
            ]),
            '*/destination/district/*' => Http::response([
                'meta' => ['message' => 'ok', 'code' => 200, 'status' => 'success'],
                'data' => [['id' => 1376, 'name' => 'BOGOR TENGAH'], ['id' => 1391, 'name' => 'GUNUNG PUTRI']],
            ]),
            '*/calculate/district/domestic-cost' => Http::response([
                'meta' => ['message' => 'ok', 'code' => 200, 'status' => 'success'],
                'data' => [
                    ['name' => 'JNE', 'code' => 'jne', 'service' => 'REG', 'description' => 'Reguler', 'cost' => 18000, 'etd' => '2-3 days'],
                    ['name' => 'J&T', 'code' => 'jnt', 'service' => 'EZ', 'description' => 'Express', 'cost' => 20000, 'etd' => '1-2 days'],
                ],
            ]),
        ]);
    }

    public function test_provinces_persist_to_db_and_hit_api_once(): void
    {
        $this->fakeRajaOngkir();
        $service = $this->app->make(RajaOngkirService::class);

        $service->provinces();
        $service->provinces();

        Http::assertSentCount(1); // provinces fetched once, second call reads DB
        $this->assertDatabaseCount('rajaongkir_provinces', 2);
        $this->assertCount(2, $service->provinces());
    }

    public function test_cities_and_districts_persist_to_db_and_hit_api_once(): void
    {
        $this->fakeRajaOngkir();
        $service = $this->app->make(RajaOngkirService::class);

        $service->cities(6);
        $service->cities(6); // second call reads DB

        Http::assertSentCount(1);
        $this->assertDatabaseCount('rajaongkir_cities', 2);

        $service->districts(455);
        $service->districts(455); // second call reads DB

        Http::assertSentCount(2);
        $this->assertDatabaseCount('rajaongkir_districts', 2);
    }

    public function test_service_caches_cost_results_for_short_ttl(): void
    {
        $this->fakeRajaOngkir();
        $service = $this->app->make(RajaOngkirService::class);

        $r1 = $service->domesticCost(1376, 400);
        $r2 = $service->domesticCost(1376, 400);

        Http::assertSentCount(1); // cost cached after first call
        $this->assertCount(2, $r1);
        $this->assertSame($r1, $r2);
        $this->assertEquals(18000, $r1[0]['cost']);
    }

    public function test_service_does_not_persist_region_on_upstream_error(): void
    {
        Http::fake([
            '*/destination/province' => Http::response([
                'meta' => ['message' => 'Invalid Api key', 'code' => 400, 'status' => 'failed'],
                'data' => null,
            ], 400),
        ]);
        $service = $this->app->make(RajaOngkirService::class);

        $this->assertSame([], $service->provinces());
        $this->assertDatabaseCount('rajaongkir_provinces', 0);
        // Not persisted => a second call retries the API.
        $this->assertSame([], $service->provinces());
        Http::assertSentCount(2);
    }

    public function test_assigned_district_matches_city(): void
    {
        $this->fakeRajaOngkir();
        $service = $this->app->make(RajaOngkirService::class);

        $service->cities(6);
        $this->assertEquals(6, (int) City::find(455)->province_id);

        $service->districts(455);
        $this->assertEquals(455, (int) District::find(1376)->city_id);
    }

    public function test_provinces_endpoint_returns_success(): void
    {
        Province::create(['id' => 6, 'name' => 'JAWA BARAT']);
        Province::create(['id' => 11, 'name' => 'DKI JAKARTA']);

        $this->get('/shipping/provinces')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data');
    }

    public function test_cities_and_districts_endpoints_respond(): void
    {
        $this->fakeRajaOngkir();

        $this->get('/shipping/cities/6')->assertOk()->assertJsonPath('data.0.id', 455);
        $this->get('/shipping/districts/455')->assertOk()->assertJsonPath('data.0.id', 1376);
    }

    public function test_shipping_cost_endpoint_calculates_from_cart_weight(): void
    {
        $this->fakeRajaOngkir();

        $product = Product::active()->first();
        $cartKey = md5("{$product->id}_500g_whole_bean");
        $cartData = [
            $cartKey => [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'weight' => '500g',
                'grind_size' => 'whole_bean',
                'unit_price' => $product->price_500g,
                'quantity' => 2,
                'subtotal' => 2 * $product->price_500g,
            ],
        ];
        $this->withSession(['limabiji_cart' => $cartData]);

        $this->post('/shipping/cost', ['district_id' => 1376])
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('weight_grams', 1000)
            ->assertJsonCount(2, 'data');
    }

    public function test_cart_service_get_total_weight_grams(): void
    {
        $product = Product::active()->first();
        $service = $this->app->make(CartService::class);

        $service->addItem($product->id, '200g', 'whole_bean', 1);
        $service->addItem($product->id, '1kg', 'coarse', 2);

        $this->assertEquals(2200, $service->getTotalWeightGrams());
    }
}

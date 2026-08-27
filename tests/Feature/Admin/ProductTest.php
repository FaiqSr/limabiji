<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    public function test_guest_is_redirected_from_products(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_products_index(): void
    {
        Product::create([
            'name' => 'Gayo Luwak Honey',
            'slug' => 'gayo-luwak-honey',
            'category' => 'arabika',
            'origin' => 'Aceh Gayo',
            'roast_level' => 'medium',
            'base_price_200g' => 85000,
            'price_500g' => 195000,
            'price_1kg' => 360000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Gayo Luwak Honey');
        $response->assertSee('Aceh Gayo');
    }

    public function test_editor_can_view_product_create_page(): void
    {
        $response = $this->actingAs($this->editor)->get(route('admin.products.create'));
        $response->assertStatus(200);
        $response->assertSee('Add New Coffee Product');
    }

    public function test_can_store_new_product(): void
    {
        $payload = [
            'name' => 'Toraja Sapan Reserve',
            'name_id' => 'Toraja Sapan Spesial',
            'slug' => 'toraja-sapan-reserve',
            'category' => 'arabika',
            'description' => 'Rich body with herbal notes.',
            'description_id' => 'Kopi dengan aroma herbal yang kaya.',
            'origin' => 'Toraja, Sulawesi',
            'altitude' => '1700 MASL',
            'process' => 'Semi-washed',
            'roast_level' => 'medium_dark',
            'sca_score' => '87.5',
            'tasting_notes' => ['Herbal', 'Dark Chocolate'],
            'flavor_tags' => ['Earthy', 'Spicy'],
            'recommended_brews' => ['v60', 'espresso'],
            'base_price_200g' => 90000,
            'price_500g' => 210000,
            'price_1kg' => 390000,
            'stock' => 30,
            'is_featured' => 1,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'slug' => 'toraja-sapan-reserve',
            'name' => 'Toraja Sapan Reserve',
            'stock' => 30,
            'is_featured' => true,
        ]);
    }

    public function test_can_update_product(): void
    {
        $product = Product::create([
            'name' => 'Bali Kintamani Natural',
            'slug' => 'bali-kintamani-natural',
            'category' => 'arabika',
            'roast_level' => 'light',
            'base_price_200g' => 75000,
            'price_500g' => 175000,
            'price_1kg' => 320000,
            'stock' => 20,
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Bali Kintamani Natural Edit',
            'slug' => 'bali-kintamani-natural',
            'category' => 'arabika',
            'roast_level' => 'medium',
            'base_price_200g' => 80000,
            'price_500g' => 180000,
            'price_1kg' => 330000,
            'stock' => 45,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->editor)->put(route('admin.products.update', $product), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Bali Kintamani Natural Edit',
            'stock' => 45,
            'base_price_200g' => 80000,
        ]);
    }

    public function test_can_toggle_active_and_featured(): void
    {
        $product = Product::create([
            'name' => 'Java Preanger',
            'slug' => 'java-preanger',
            'category' => 'arabika',
            'roast_level' => 'medium',
            'base_price_200g' => 70000,
            'price_500g' => 160000,
            'price_1kg' => 300000,
            'stock' => 15,
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->actingAs($this->admin)->post(route('admin.products.toggle-active', $product));
        $this->assertFalse($product->fresh()->is_active);

        $this->actingAs($this->admin)->post(route('admin.products.toggle-featured', $product));
        $this->assertTrue($product->fresh()->is_featured);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Papua Wamena',
            'slug' => 'papua-wamena',
            'category' => 'arabika',
            'roast_level' => 'medium',
            'base_price_200g' => 95000,
            'price_500g' => 220000,
            'price_1kg' => 400000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));
        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

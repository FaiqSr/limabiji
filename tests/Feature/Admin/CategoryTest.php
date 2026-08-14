<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
        $this->editor = User::where('role', 'editor')->first() ?? User::factory()->create(['role' => 'editor']);
    }

    public function test_guests_cannot_access_category_management(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_and_editor_can_view_category_index(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('News Categories Management');

        $responseEditor = $this->actingAs($this->editor)->get(route('admin.categories.index'));
        $responseEditor->assertStatus(200);
    }

    public function test_can_create_category_with_bilingual_names(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Market Trends',
            'name_id' => 'Tren Pasar',
            'slug' => 'market-trends',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Market Trends',
            'name_id' => 'Tren Pasar',
            'slug' => 'market-trends',
        ]);
    }

    public function test_validates_unique_slug(): void
    {
        Category::create([
            'name' => 'Research',
            'name_id' => 'Riset',
            'slug' => 'research',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Another Research',
            'slug' => 'research',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Old Name',
            'name_id' => 'Nama Lama',
            'slug' => 'old-name',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated Name',
            'name_id' => 'Nama Baru',
            'slug' => 'updated-name',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'name_id' => 'Nama Baru',
            'slug' => 'updated-name',
        ]);
    }

    public function test_can_delete_category_and_unlink_articles(): void
    {
        $category = Category::create([
            'name' => 'Temporary Category',
            'slug' => 'temporary-category',
        ]);

        $article = Article::create([
            'title' => 'Article with temporary category',
            'slug' => 'article-temporary-category',
            'status' => 'draft',
            'author_id' => $this->admin->id,
        ]);

        $article->categories()->attach($category->id);

        $this->assertDatabaseHas('article_category', [
            'article_id' => $article->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('article_category', ['category_id' => $category->id]);
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }

    public function test_can_search_categories(): void
    {
        Category::create(['name' => 'UniqueSearchableTag', 'slug' => 'unique-searchable-tag']);
        Category::create(['name' => 'OtherTag', 'slug' => 'other-tag']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index', ['search' => 'UniqueSearchableTag']));
        $response->assertStatus(200);
        $response->assertSee('UniqueSearchableTag');
        $response->assertDontSee('OtherTag');
    }
}

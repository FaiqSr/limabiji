<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageEditorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_access_page_editor(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = Page::first();

        $response = $this->actingAs($admin)->get("/admin/content/{$page->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Editing Page');
    }

    public function test_admin_can_update_page_with_all_11_block_types(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = Page::first();

        $blocksData = [
            [
                'id' => null,
                'block_type' => 'hero',
                'order' => 0,
                'is_visible' => 1,
                'content' => [
                    'en' => ['label' => 'HERO', 'heading' => 'WELCOME'],
                    'id' => ['label' => 'HERO', 'heading' => 'SELAMAT DATANG'],
                ],
            ],
            [
                'id' => null,
                'block_type' => 'origins',
                'order' => 1,
                'is_visible' => 1,
                'content' => [
                    'en' => ['heading' => 'ORIGINS', 'items' => [['name' => 'Bogor', 'slug' => 'bogor']]],
                    'id' => ['heading' => 'ASAL KOPI', 'items' => [['name' => 'Bogor', 'slug' => 'bogor']]],
                ],
            ],
            [
                'id' => null,
                'block_type' => 'export_map',
                'order' => 2,
                'is_visible' => 1,
                'content' => [
                    'en' => ['heading' => 'EXPORT MAP', 'items' => [['name' => 'Japan', 'country_code' => 'jp']]],
                    'id' => ['heading' => 'PETA EKSPOR', 'items' => [['name' => 'Jepang', 'country_code' => 'jp']]],
                ],
            ],
            [
                'id' => null,
                'block_type' => 'contact',
                'order' => 3,
                'is_visible' => 1,
                'content' => [
                    'en' => ['heading' => 'CONTACT US', 'email' => 'contact@limabiji.com'],
                    'id' => ['heading' => 'HUBUNGI KAMI', 'email' => 'contact@limabiji.com'],
                ],
            ],
        ];

        $response = $this->actingAs($admin)->put("/admin/content/{$page->id}", [
            'title' => $page->title,
            'slug' => $page->slug,
            'is_published' => 1,
            'blocks' => $blocksData,
        ]);

        $response->assertRedirect("/admin/content/{$page->id}/edit");
        $this->assertDatabaseHas('page_blocks', [
            'page_id' => $page->id,
            'block_type' => 'origins',
        ]);
        $this->assertDatabaseHas('page_blocks', [
            'page_id' => $page->id,
            'block_type' => 'export_map',
        ]);
        $this->assertDatabaseHas('page_blocks', [
            'page_id' => $page->id,
            'block_type' => 'contact',
        ]);
    }

    public function test_admin_can_render_realtime_iframe_preview(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->postJson('/admin/content/preview', [
            'title' => 'Test Preview',
            'locale' => 'en',
            'blocks' => [
                [
                    'id' => 1,
                    'block_type' => 'hero',
                    'order' => 0,
                    'is_visible' => true,
                    'content' => [
                        'en' => ['label' => 'PREVIEW LABEL', 'heading' => 'PREVIEW HEADING'],
                    ],
                ],
                [
                    'id' => 2,
                    'block_type' => 'export_map',
                    'order' => 1,
                    'is_visible' => true,
                    'content' => [
                        'en' => ['heading' => 'PREVIEW MAP'],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertSee('PREVIEW HEADING');
        $response->assertSee('PREVIEW MAP');
    }

    public function test_page_editor_persists_field_visibility_state(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = Page::first();

        $blocksData = [
            [
                'id' => null,
                'block_type' => 'hero',
                'order' => 0,
                'is_visible' => 1,
                'content' => [
                    'en' => [
                        'label' => 'HERO BADGE',
                        'heading' => 'HERO HEADING',
                        'field_hidden' => [
                            'label' => 1,
                            'heading' => 0,
                        ],
                    ],
                    'id' => [
                        'label' => 'HERO BADGE ID',
                        'heading' => 'HERO HEADING ID',
                        'field_hidden' => [
                            'label' => 1,
                            'heading' => 0,
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($admin)->put("/admin/content/{$page->id}", [
            'title' => $page->title,
            'slug' => $page->slug,
            'is_published' => 1,
            'blocks' => $blocksData,
        ]);

        $response->assertRedirect("/admin/content/{$page->id}/edit");

        $block = $page->fresh()->blocks()->first();
        $this->assertNotNull($block);
        $this->assertTrue($block->isFieldHidden('en', 'label'));
        $this->assertFalse($block->isFieldHidden('en', 'heading'));
    }
}

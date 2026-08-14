<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ExportDestination;
use App\Models\Origin;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('blocks')->latest()->paginate(20);

        return view('admin.content.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.content.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')],
            'is_published' => ['boolean'],
        ]);

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'is_published' => $validated['is_published'] ?? false,
            'updated_by' => auth()->id(),
        ]);

        $page->blocks()->create([
            'block_type' => 'hero',
            'order' => 0,
            'content' => [
                'en' => [
                    'label' => $page->title,
                    'heading' => strtoupper($page->title),
                    'subheading' => 'Welcome to '.$page->title.' page.',
                ],
                'id' => [
                    'label' => $page->title,
                    'heading' => strtoupper($page->title),
                    'subheading' => 'Selamat datang di halaman '.$page->title.'.',
                ],
            ],
            'is_visible' => true,
        ]);

        return redirect()
            ->route('admin.content.edit', $page)
            ->with('success', 'Page created successfully. You can now customize content blocks.');
    }

    public function destroy(Page $page)
    {
        $page->blocks()->delete();
        $page->versions()->delete();
        $page->delete();

        return redirect()
            ->route('admin.content.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function edit(Page $page)
    {
        $page->load(['blocks' => fn ($q) => $q->orderBy('order'), 'versions']);

        return view('admin.content.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')->ignore($page->id)],
            'is_published' => ['boolean'],
            'blocks' => ['array'],
            'blocks.*.id' => ['nullable', 'integer'],
            'blocks.*.block_type' => ['required', 'string', 'in:hero,text,stats,faq,cta,process_steps,text_with_stats,articles,testimonials,origins,export_map,contact'],
            'blocks.*.order' => ['required', 'integer', 'min:0'],
            'blocks.*.content' => ['required', 'array'],
            'blocks.*.content.en' => ['array'],
            'blocks.*.content.id' => ['array'],
            'blocks.*.is_visible' => ['boolean'],
        ]);

        // Save version snapshot before updating
        PageVersion::createSnapshot($page, auth()->id());

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'is_published' => $validated['is_published'] ?? false,
            'updated_by' => auth()->id(),
        ]);

        // Sync blocks
        if (isset($validated['blocks'])) {
            $existingIds = $page->blocks()->pluck('id')->toArray();
            $submittedIds = [];

            foreach ($validated['blocks'] as $blockData) {
                $blockId = $blockData['id'] ?? null;

                // Merge with existing content to preserve other locale
                $existingContent = [];
                $existingBlock = null;
                if ($blockId && in_array($blockId, $existingIds)) {
                    $existingBlock = $page->blocks()->find($blockId);
                    $existingContent = $existingBlock && is_array($existingBlock->content) ? $existingBlock->content : [];
                }

                $mergedContent = $existingContent;
                if (isset($blockData['content']) && is_array($blockData['content'])) {
                    foreach (['en', 'id'] as $loc) {
                        if (isset($blockData['content'][$loc]) && is_array($blockData['content'][$loc])) {
                            $locData = $blockData['content'][$loc];
                            unset($locData['_preserve']);

                            if (! empty($locData)) {
                                $mergedContent[$loc] = array_merge(
                                    $existingContent[$loc] ?? [],
                                    $locData
                                );
                            }
                        }
                    }
                }

                $content = $this->normalizeBlockContent($blockData['block_type'], $mergedContent);
                $blockAttrs = [
                    'block_type' => $blockData['block_type'],
                    'order' => $blockData['order'],
                    'content' => $content,
                    'is_visible' => $blockData['is_visible'] ?? true,
                ];

                if ($existingBlock) {
                    $existingBlock->update($blockAttrs);
                    $submittedIds[] = $existingBlock->id;
                } else {
                    $newBlock = $page->blocks()->create($blockAttrs);
                    $submittedIds[] = $newBlock->id;
                }
            }

            // Delete removed blocks
            $toDelete = array_diff($existingIds, $submittedIds);
            if (! empty($toDelete)) {
                $page->blocks()->whereIn('id', $toDelete)->delete();
            }
        }

        return redirect()
            ->route('admin.content.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    private function normalizeBlockContent(string $type, array $content): array
    {
        if (! in_array($type, ['stats', 'faq', 'process_steps', 'text_with_stats', 'articles', 'testimonials', 'origins', 'export_map'])) {
            return $content;
        }

        // Sync 'limit' and 'show_all' across locales if set in any locale
        $sharedShowAll = ! empty($content['en']['show_all']) || ! empty($content['id']['show_all']);
        $sharedLimit = $sharedShowAll ? 0 : ($content['en']['limit'] ?? ($content['id']['limit'] ?? null));

        if ($sharedLimit !== null && $sharedLimit !== '') {
            $content['en']['limit'] = $sharedLimit;
            $content['id']['limit'] = $sharedLimit;
        }

        $content['en']['show_all'] = $sharedShowAll;
        $content['id']['show_all'] = $sharedShowAll;

        foreach (['en', 'id'] as $locale) {
            $items = $content[$locale]['items'] ?? [];

            if (is_string($items)) {
                $items = json_decode($items, true) ?: [];
            }

            $content[$locale]['items'] = array_values(array_filter($items, function ($item) {
                return is_array($item) && count(array_filter($item, fn ($value) => filled($value))) > 0;
            }));
        }

        return $content;
    }

    public function restoreVersion(Request $request, Page $page, PageVersion $version)
    {
        // Restore blocks from snapshot
        $page->blocks()->delete();
        foreach ($version->content_snapshot as $block) {
            $page->blocks()->create([
                'block_type' => $block['block_type'],
                'order' => $block['order'] ?? 0,
                'content' => $block['content'],
                'is_visible' => $block['is_visible'] ?? true,
            ]);
        }

        // Create new version for the restore action
        PageVersion::createSnapshot($page, auth()->id());

        return redirect()
            ->route('admin.content.edit', $page)
            ->with('success', "Restored to version {$version->version_number}.");
    }

    public function preview(Request $request)
    {
        $blocksData = $request->input('blocks', []);
        $locale = $request->input('locale', 'en');

        $origins = Origin::active()->ordered()->get();
        $testimonials = Testimonial::orderBy('order')->take(24)->get();
        $settings = SiteSetting::all()->keyBy('key');
        $exportDestinations = ExportDestination::active()->orderBy('order')->get();
        $articles = Article::where('status', 'published')->forLocale($locale)->latest()->take(24)->get();

        $blocks = collect($blocksData)->map(function ($blockData, $index) {
            $content = $blockData['content'] ?? [];
            if (is_string($content)) {
                try {
                    $content = json_decode($content, true) ?: [];
                } catch (\Throwable $e) {
                }
            }

            return new class($blockData, $content, $index)
            {
                public $id;

                public $block_type;

                public $order;

                public $is_visible;

                public $content;

                public function __construct($data, $content, $index)
                {
                    $this->id = $data['id'] ?? ($index + 1);
                    $this->block_type = $data['block_type'] ?? 'hero';
                    $this->order = $data['order'] ?? $index;
                    $this->is_visible = isset($data['is_visible']) ? filter_var($data['is_visible'], FILTER_VALIDATE_BOOLEAN) : true;
                    $this->content = $content;
                }

                public function getContent($locale = 'en')
                {
                    if (isset($this->content[$locale]) && is_array($this->content[$locale])) {
                        return $this->content[$locale];
                    }

                    return is_array($this->content) ? $this->content : [];
                }
            };
        });

        $page = (object) [
            'title' => $request->input('title', 'Preview Page'),
            'blocks' => $blocks,
        ];

        return view('admin.content.preview_iframe', compact(
            'page',
            'origins',
            'articles',
            'testimonials',
            'settings',
            'exportDestinations',
            'locale'
        ));
    }
}

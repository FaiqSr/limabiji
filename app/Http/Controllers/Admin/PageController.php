<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageVersion;
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
            'blocks.*.block_type' => ['required', 'string', 'in:hero,text,stats,faq,cta,process_steps,text_with_stats,articles,testimonials'],
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
        if (! in_array($type, ['stats', 'faq', 'process_steps', 'text_with_stats', 'articles', 'testimonials'])) {
            return $content;
        }

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
}

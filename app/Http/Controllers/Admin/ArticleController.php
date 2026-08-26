<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['author', 'categories'])->latest();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_id', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('excerpt_id', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($catQ) use ($search) {
                        $catQ->where('name', 'like', "%{$search}%")
                            ->orWhere('name_id', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->get('status')) {
            if (in_array($status, ['draft', 'published'])) {
                $query->where('status', $status);
            }
        }

        if ($lang = $request->get('lang')) {
            if ($lang === 'en') {
                $query->whereNotNull('content')->where('content', '!=', '');
            } elseif ($lang === 'id') {
                $query->whereNotNull('content_id')->where('content_id', '!=', '');
            }
        }

        $perPage = (int) $request->get('per_page', 6);
        if (! in_array($perPage, [6, 12, 24, 48])) {
            $perPage = 6;
        }

        $articles = $query->paginate($perPage)->withQueryString();

        return view('admin.news.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.news.edit', [
            'article' => new Article,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('articles')],
            'category' => ['nullable', 'string', 'max:100'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_id' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_id' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['author_id'] = auth()->id();

        // If category text is empty but category_ids are provided, populate string fallback
        $categoryIds = $request->input('category_ids', []);
        if (empty($validated['category']) && ! empty($categoryIds)) {
            $firstCategory = Category::find($categoryIds[0]);
            $validated['category'] = $firstCategory?->name ?? 'Blog';
        }

        $article = Article::create($validated);
        $article->categories()->sync($categoryIds);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $article->load('categories');
        $categories = Category::orderBy('name')->get();

        return view('admin.news.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('articles')->ignore($article->id)],
            'category' => ['nullable', 'string', 'max:100'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_id' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_id' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        $categoryIds = $request->input('category_ids', []);
        if (empty($validated['category']) && ! empty($categoryIds)) {
            $firstCategory = Category::find($categoryIds[0]);
            $validated['category'] = $firstCategory?->name ?? $article->category;
        }

        $article->update($validated);
        $article->categories()->sync($categoryIds);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->categories()->detach();
        $article->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Article deleted.');
    }
}

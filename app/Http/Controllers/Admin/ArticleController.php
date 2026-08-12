<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('author')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($lang = $request->get('lang')) {
            if ($lang === 'en') {
                $query->whereNotNull('content')->where('content', '!=', '');
            } elseif ($lang === 'id') {
                $query->whereNotNull('content_id')->where('content_id', '!=', '');
            }
        }

        $articles = $query->paginate(20)->withQueryString();
        $pendingCount = Article::where('status', 'pending')->count();

        return view('admin.news.index', compact('articles', 'pendingCount'));
    }

    public function create()
    {
        return view('admin.news.edit', ['article' => new Article]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('articles')],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_id' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_id' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,pending,approved,published,rejected'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['author_id'] = auth()->id();

        $article = Article::create($validated);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        return view('admin.news.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('articles')->ignore($article->id)],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_id' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_id' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,pending,approved,published,rejected'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        $article->update($validated);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Article deleted.');
    }
}

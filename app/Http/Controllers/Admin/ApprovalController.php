<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingArticles = Article::where('status', 'pending')
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('admin.approvals.index', compact('pendingArticles'));
    }

    public function approve(Article $article)
    {
        $scheduled = $article->published_at && $article->published_at->isFuture();

        $article->update([
            'status' => $scheduled ? 'approved' : 'published',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'published_at' => $article->published_at ?? now(),
        ]);

        $message = $scheduled
            ? 'Article approved and scheduled for publication.'
            : 'Article approved and published.';

        return back()->with('success', $message);
    }

    public function reject(Article $article)
    {
        $article->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Article rejected.');
    }
}

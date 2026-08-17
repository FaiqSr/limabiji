<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('question_id', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%")
                    ->orWhere('answer_id', 'like', "%{$search}%");
            });
        }

        // Status filter
        $status = $request->get('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $faqs = $query->orderBy('order')->orderBy('id')->paginate(15)->withQueryString();

        $counts = [
            'all' => Faq::count(),
            'active' => Faq::where('is_active', true)->count(),
            'inactive' => Faq::where('is_active', false)->count(),
        ];

        return view('admin.faqs.index', compact('faqs', 'counts', 'status'));
    }

    public function create()
    {
        $nextOrder = (Faq::max('order') ?? 0) + 1;

        return view('admin.faqs.create', [
            'faq' => new Faq(['order' => $nextOrder, 'is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'question_id' => ['nullable', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'answer_id' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? ((Faq::max('order') ?? 0) + 1);

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ item created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'question_id' => ['nullable', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'answer_id' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ item updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ item deleted successfully.');
    }

    public function toggleActive(Faq $faq)
    {
        $faq->update([
            'is_active' => ! $faq->is_active,
        ]);

        $statusText = $faq->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "FAQ item {$statusText} successfully.");
    }
}

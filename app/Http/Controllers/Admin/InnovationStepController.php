<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InnovationStep;
use Illuminate\Http\Request;

class InnovationStepController extends Controller
{
    public function index(Request $request)
    {
        $query = InnovationStep::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('title_id', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('description_id', 'like', "%{$searchTerm}%")
                    ->orWhere('step_number', 'like', "%{$searchTerm}%");
            });
        }

        $status = $request->get('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $steps = $query->orderBy('order')->orderBy('id')->paginate(15)->withQueryString();

        $counts = [
            'all' => InnovationStep::count(),
            'active' => InnovationStep::where('is_active', true)->count(),
            'inactive' => InnovationStep::where('is_active', false)->count(),
        ];

        return view('admin.innovation-steps.index', compact('steps', 'counts', 'status'));
    }

    public function create()
    {
        $nextOrder = (InnovationStep::max('order') ?? 0) + 1;
        $suggestedStepNumber = str_pad((string) $nextOrder, 2, '0', STR_PAD_LEFT);

        return view('admin.innovation-steps.create', compact('nextOrder', 'suggestedStepNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => ['nullable', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_id' => ['nullable', 'string'],
            'details' => ['nullable', 'string', 'max:500'],
            'details_id' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:1000'],
            'order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['step_number'])) {
            $validated['step_number'] = str_pad((string) $validated['order'], 2, '0', STR_PAD_LEFT);
        }

        InnovationStep::create($validated);

        return redirect()->route('admin.innovation-steps.index')
            ->with('success', 'Innovation process step created successfully.');
    }

    public function edit(InnovationStep $innovationStep)
    {
        return view('admin.innovation-steps.edit', compact('innovationStep'));
    }

    public function update(Request $request, InnovationStep $innovationStep)
    {
        $validated = $request->validate([
            'step_number' => ['nullable', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:255'],
            'title_id' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'description_id' => ['nullable', 'string'],
            'details' => ['nullable', 'string', 'max:500'],
            'details_id' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:1000'],
            'order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['step_number'])) {
            $validated['step_number'] = str_pad((string) $validated['order'], 2, '0', STR_PAD_LEFT);
        }

        $innovationStep->update($validated);

        return redirect()->route('admin.innovation-steps.index')
            ->with('success', 'Innovation process step updated successfully.');
    }

    public function destroy(InnovationStep $innovationStep)
    {
        $innovationStep->delete();

        return redirect()->route('admin.innovation-steps.index')
            ->with('success', 'Innovation process step deleted successfully.');
    }

    public function toggleActive(InnovationStep $innovationStep)
    {
        $innovationStep->update([
            'is_active' => ! $innovationStep->is_active,
        ]);

        $status = $innovationStep->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Process step {$status} successfully.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExportDestination;
use Illuminate\Http\Request;

class ExportDestinationController extends Controller
{
    public function index()
    {
        $destinations = ExportDestination::orderBy('order')->orderBy('name')->get();

        return view('admin.export_destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.export_destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_id' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:5',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'description' => 'nullable|string',
            'description_id' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['country_code'] = strtolower(trim($validated['country_code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        ExportDestination::create($validated);

        return redirect()->route('admin.export-destinations.index')
            ->with('success', 'Export destination created successfully.');
    }

    public function edit(ExportDestination $exportDestination)
    {
        return view('admin.export_destinations.edit', ['destination' => $exportDestination]);
    }

    public function update(Request $request, ExportDestination $exportDestination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_id' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:5',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'description' => 'nullable|string',
            'description_id' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['country_code'] = strtolower(trim($validated['country_code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $exportDestination->update($validated);

        return redirect()->route('admin.export-destinations.index')
            ->with('success', 'Export destination updated successfully.');
    }

    public function destroy(ExportDestination $exportDestination)
    {
        $exportDestination->delete();

        return redirect()->route('admin.export-destinations.index')
            ->with('success', 'Export destination deleted successfully.');
    }
}

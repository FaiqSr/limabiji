<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('issuer', 'like', "%{$search}%")
                    ->orWhere('certificate_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        $status = $request->get('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $certificates = $query->orderBy('order')->orderBy('id')->paginate(15)->withQueryString();

        $counts = [
            'all' => Certificate::count(),
            'active' => Certificate::where('is_active', true)->count(),
            'inactive' => Certificate::where('is_active', false)->count(),
        ];

        return view('admin.certificates.index', compact('certificates', 'counts', 'status'));
    }

    public function create()
    {
        $nextOrder = (Certificate::max('order') ?? 0) + 1;

        return view('admin.certificates.create', [
            'certificate' => new Certificate(['order' => $nextOrder, 'is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:1000'],
            'logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:2000'],
            'issued_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('certificates', 'public');
            $validated['logo'] = '/storage/'.$path;
        }

        if (empty($validated['logo'])) {
            $validated['logo'] = 'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=300&auto=format&fit=crop';
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? ((Certificate::max('order') ?? 0) + 1);

        unset($validated['logo_file']);

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate added successfully.');
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:1000'],
            'logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:2000'],
            'issued_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('certificates', 'public');
            $validated['logo'] = '/storage/'.$path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        unset($validated['logo_file']);

        $certificate->update($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    public function toggleActive(Certificate $certificate)
    {
        $certificate->update([
            'is_active' => ! $certificate->is_active,
        ]);

        $statusText = $certificate->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Certificate {$statusText} successfully.");
    }
}

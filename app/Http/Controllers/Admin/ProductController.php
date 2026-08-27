<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_id', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('stock_status')) {
            if ($request->input('stock_status') === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->input('stock_status') === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            } elseif ($request->input('stock_status') === 'in_stock') {
                $query->where('stock', '>', 10);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $categories = ['arabika', 'robusta', 'blend', 'experimental'];

        return view('admin.store.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $product = new Product;
        $categories = ['arabika', 'robusta', 'blend', 'experimental'];
        $roastLevels = ['light', 'medium', 'medium_dark', 'dark'];

        return view('admin.store.products.create', compact('product', 'categories', 'roastLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products')],
            'category' => ['required', 'string', 'in:arabika,robusta,blend,experimental'],
            'description' => ['nullable', 'string'],
            'description_id' => ['nullable', 'string'],
            'origin' => ['nullable', 'string', 'max:255'],
            'altitude' => ['nullable', 'string', 'max:255'],
            'process' => ['nullable', 'string', 'max:255'],
            'roast_level' => ['required', 'string', 'in:light,medium,medium_dark,dark'],
            'sca_score' => ['nullable', 'string', 'max:20'],
            'tasting_notes' => ['nullable', 'array'],
            'tasting_notes.*' => ['string'],
            'flavor_tags' => ['nullable', 'array'],
            'flavor_tags.*' => ['string'],
            'recommended_brews' => ['nullable', 'array'],
            'recommended_brews.*' => ['string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'base_price_200g' => ['required', 'integer', 'min:0'],
            'price_500g' => ['required', 'integer', 'min:0'],
            'price_1kg' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['tasting_notes'] = array_values(array_filter($validated['tasting_notes'] ?? []));
        $validated['flavor_tags'] = array_values(array_filter($validated['flavor_tags'] ?? []));
        $validated['recommended_brews'] = array_values(array_filter($validated['recommended_brews'] ?? []));

        $product = Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product '{$product->name}' created successfully.");
    }

    public function edit(Product $product)
    {
        $categories = ['arabika', 'robusta', 'blend', 'experimental'];
        $roastLevels = ['light', 'medium', 'medium_dark', 'dark'];

        return view('admin.store.products.edit', compact('product', 'categories', 'roastLevels'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_id' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category' => ['required', 'string', 'in:arabika,robusta,blend,experimental'],
            'description' => ['nullable', 'string'],
            'description_id' => ['nullable', 'string'],
            'origin' => ['nullable', 'string', 'max:255'],
            'altitude' => ['nullable', 'string', 'max:255'],
            'process' => ['nullable', 'string', 'max:255'],
            'roast_level' => ['required', 'string', 'in:light,medium,medium_dark,dark'],
            'sca_score' => ['nullable', 'string', 'max:20'],
            'tasting_notes' => ['nullable', 'array'],
            'tasting_notes.*' => ['string'],
            'flavor_tags' => ['nullable', 'array'],
            'flavor_tags.*' => ['string'],
            'recommended_brews' => ['nullable', 'array'],
            'recommended_brews.*' => ['string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'base_price_200g' => ['required', 'integer', 'min:0'],
            'price_500g' => ['required', 'integer', 'min:0'],
            'price_1kg' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['tasting_notes'] = array_values(array_filter($validated['tasting_notes'] ?? []));
        $validated['flavor_tags'] = array_values(array_filter($validated['flavor_tags'] ?? []));
        $validated['recommended_brews'] = array_values(array_filter($validated['recommended_brews'] ?? []));

        // Clean up replaced image if applicable
        if ($product->image && $product->image !== ($validated['image'] ?? null)) {
            $this->cleanupStorageImage($product->image);
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;

        if ($product->image) {
            $this->cleanupStorageImage($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product '{$name}' deleted.");
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Product status updated to '.($product->is_active ? 'Active' : 'Inactive').'.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return back()->with('success', 'Product featured status updated to '.($product->is_featured ? 'Featured' : 'Standard').'.');
    }

    private function cleanupStorageImage(?string $url): void
    {
        if (empty($url)) {
            return;
        }

        $path = null;
        if (str_contains($url, '/storage/')) {
            $path = Str::after($url, '/storage/');
        } elseif (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $path = ltrim($url, '/');
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

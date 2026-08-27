@extends('admin.layouts.app')
@section('title', 'Products')
@section('page_title', 'Store Products Management')

@section('content')
<div class="space-y-6">

    <!-- Header / Action Bar -->
    <div class="card-modern flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-slate-900">Coffee Products Catalog</h3>
                <span class="text-[11px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $products->total() }} {{ Str::plural('ITEM', $products->total()) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Manage specialty beans, roast profiles, multi-weight pricing, and live inventory stock.</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary text-xs py-2 px-4 shadow-2xs w-full sm:w-fit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add New Product</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="card-modern p-3.5">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5">
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, origin..." class="text-xs">
            </div>

            <div>
                <select name="category" class="text-xs">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="stock_status" class="text-xs">
                    <option value="">All Stock Levels</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (> 10)</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock (≤ 10)</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-secondary text-xs py-2 px-3.5 flex-1">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'category', 'stock_status', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="btn text-xs py-2 px-3 text-slate-500 hover:text-slate-800 border border-slate-200" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="w-14">Image</th>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th>Pricing (200g / 500g / 1kg)</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="w-11 h-11 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if ($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="font-semibold text-slate-900 text-xs hover:text-emerald-700 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                        @if ($product->is_featured)
                                            <span class="px-1.5 py-0.2 text-[9px] font-bold rounded bg-amber-50 text-amber-700 border border-amber-200 font-mono uppercase">
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                                        {{ $product->origin ?? 'Single Origin' }} &bull; {{ ucfirst(str_replace('_', ' ', $product->roast_level)) }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span class="text-[10px] font-bold font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td>
                                <div class="text-xs font-mono space-y-0.5">
                                    <div class="text-slate-800 font-medium">200g: Rp {{ number_format($product->base_price_200g, 0, ',', '.') }}</div>
                                    <div class="text-slate-400 text-[10px]">500g: Rp {{ number_format($product->price_500g, 0, ',', '.') }} | 1kg: Rp {{ number_format($product->price_1kg, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td>
                                @if ($product->stock <= 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Out of stock
                                    </span>
                                @elseif ($product->stock <= 10)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                        {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                        {{ $product->stock }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase border transition-colors cursor-pointer {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" title="Toggle Featured on Storefront">
                                        @csrf
                                        <button type="submit" class="p-1 rounded text-slate-300 hover:text-amber-500 transition-colors cursor-pointer {{ $product->is_featured ? 'text-amber-500' : '' }}">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary text-xs py-1 px-2.5">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete product \'{{ $product->name }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger text-xs py-1 px-2.5 cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-500">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-slate-700">No products found</p>
                                    <p class="text-[11px] text-slate-400">Try adjusting your search query or filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-3.5 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

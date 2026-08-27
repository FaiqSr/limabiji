<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category', 'all');
        $search = $request->query('q', '');
        $sort = $request->query('sort', 'featured');

        $query = Product::active();

        // Filter Category
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Search Query
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_id', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('description_id', 'like', "%{$search}%");
            });
        }

        // Sorting
        match ($sort) {
            'price_asc' => $query->orderBy('base_price_200g', 'asc'),
            'price_desc' => $query->orderBy('base_price_200g', 'desc'),
            'sca_desc' => $query->orderBy('sca_score', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('id', 'asc'),
        };

        $products = $query->paginate(8)->withQueryString();
        $featuredProducts = Product::active()->featured()->take(3)->get();

        return view('landingpages.store.index', compact('products', 'featuredProducts', 'category', 'search', 'sort'));
    }

    public function show(string $slug): View
    {
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(3)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::active()
                ->where('id', '!=', $product->id)
                ->take(3)
                ->get();
        }

        return view('landingpages.store.show', compact('product', 'relatedProducts'));
    }

    public function quiz(Request $request): JsonResponse
    {
        $brew = $request->input('brew'); // v60, espresso, tubruk, cold_brew, french_press
        $flavor = $request->input('flavor'); // fruity, sweet, bold
        $roast = $request->input('roast'); // light, medium, dark

        $products = Product::active()->get();

        $scored = $products->map(function (Product $product) use ($brew, $flavor, $roast) {
            $score = 0;

            // Brew match
            if ($brew && is_array($product->recommended_brews) && in_array($brew, $product->recommended_brews)) {
                $score += 40;
            }

            // Flavor match
            if ($flavor && is_array($product->flavor_tags)) {
                foreach ($product->flavor_tags as $tag) {
                    if (stripos($tag, $flavor) !== false) {
                        $score += 35;
                        break;
                    }
                }
            }

            // Roast match
            if ($roast && $product->roast_level) {
                if ($roast === 'light' && in_array($product->roast_level, ['light'])) {
                    $score += 25;
                } elseif ($roast === 'medium' && in_array($product->roast_level, ['medium', 'medium_dark'])) {
                    $score += 25;
                } elseif ($roast === 'dark' && in_array($product->roast_level, ['dark', 'medium_dark'])) {
                    $score += 25;
                }
            }

            if ($product->is_featured) {
                $score += 5;
            }

            return [
                'product' => $product,
                'match_score' => $score,
            ];
        });

        $topRecommendations = $scored->sortByDesc('match_score')->take(3)->values();

        $locale = app()->getLocale();
        $results = $topRecommendations->map(function ($item) use ($locale) {
            /** @var Product $p */
            $p = $item['product'];

            return [
                'id' => $p->id,
                'name' => $p->getNameForLocale($locale),
                'slug' => $p->slug,
                'category' => ucfirst($p->category),
                'origin' => $p->origin,
                'roast_level' => ucfirst(str_replace('_', ' ', $p->roast_level)),
                'sca_score' => $p->sca_score,
                'tasting_notes' => $p->tasting_notes,
                'image' => $p->image,
                'formatted_price' => $p->getFormattedPrice('200g'),
                'match_percentage' => min(98, 65 + $item['match_score']),
                'url' => route('store.show', $p->slug),
            ];
        });

        return response()->json([
            'status' => 'success',
            'recommendations' => $results,
        ]);
    }
}

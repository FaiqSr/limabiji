<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'cart' => $this->cartService->getCart(),
            'item_count' => $this->cartService->getItemCount(),
            'subtotal' => $this->cartService->getSubtotal(),
            'formatted_subtotal' => $this->cartService->getFormattedSubtotal(),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'weight' => 'nullable|string|in:200g,500g,1kg',
            'grind_size' => 'nullable|string|in:whole_bean,coarse,medium,fine',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $weight = $validated['weight'] ?? '200g';
        $grindSize = $validated['grind_size'] ?? 'whole_bean';
        $quantity = (int) ($validated['quantity'] ?? 1);

        try {
            $cart = $this->cartService->addItem($validated['product_id'], $weight, $grindSize, $quantity);

            return response()->json([
                'status' => 'success',
                'message' => __('store.cart_added_success'),
                'cart' => $cart,
                'item_count' => $this->cartService->getItemCount(),
                'subtotal' => $this->cartService->getSubtotal(),
                'formatted_subtotal' => $this->cartService->getFormattedSubtotal(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $cart = $this->cartService->updateQuantity($validated['key'], (int) $validated['quantity']);

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'item_count' => $this->cartService->getItemCount(),
            'subtotal' => $this->cartService->getSubtotal(),
            'formatted_subtotal' => $this->cartService->getFormattedSubtotal(),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        $cart = $this->cartService->removeItem($validated['key']);

        return response()->json([
            'status' => 'success',
            'message' => __('store.cart_removed_success'),
            'cart' => $cart,
            'item_count' => $this->cartService->getItemCount(),
            'subtotal' => $this->cartService->getSubtotal(),
            'formatted_subtotal' => $this->cartService->getFormattedSubtotal(),
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();

        return response()->json([
            'status' => 'success',
            'message' => __('store.cart_cleared_success'),
            'cart' => [],
            'item_count' => 0,
            'subtotal' => 0,
            'formatted_subtotal' => 'Rp 0',
        ]);
    }
}

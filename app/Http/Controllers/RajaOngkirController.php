<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\RajaOngkirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function __construct(
        protected RajaOngkirService $rajaOngkir,
        protected CartService $cartService
    ) {}

    public function provinces(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->rajaOngkir->provinces(),
        ]);
    }

    public function cities(int $provinceId): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->rajaOngkir->cities($provinceId),
        ]);
    }

    public function districts(int $cityId): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->rajaOngkir->districts($cityId),
        ]);
    }

    /**
     * Calculate shipping costs for the current cart weight to a destination district.
     */
    public function shippingCost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'district_id' => 'required|integer|min:1',
        ]);

        $weightGrams = $this->cartService->getTotalWeightGrams();
        $data = $this->rajaOngkir->domesticCost((int) $validated['district_id'], $weightGrams);

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => __('store.shipping_cost_not_available'),
                'data' => [],
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'weight_grams' => $weightGrams,
        ]);
    }
}

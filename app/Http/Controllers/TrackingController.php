<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    /**
     * Public order tracker. Look up an order by its order number and show
     * the latest fulfillment status (payment + shipping progress).
     */
    public function index(Request $request): View
    {
        $query = strtoupper(trim((string) $request->query('order', '')));
        $order = null;

        if ($query !== '') {
            $order = Order::with('items')
                ->where('order_number', $query)
                ->first();
        }

        return view('landingpages.store.tracking', compact('order', 'query'));
    }
}

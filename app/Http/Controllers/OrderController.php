<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Customers can only read their own orders. */
class OrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->withCount('items')->latest('id')->paginate(10),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        // Ownership check: another customer's order is forbidden, not just hidden.
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', ['order' => $order->load(['items', 'histories'])]);
    }
}

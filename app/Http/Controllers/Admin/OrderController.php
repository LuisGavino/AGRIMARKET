<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

/**
 * Administrators can view orders and move their status along the allowed path. Items,
 * totals and the customer are never edited here, and orders cannot be deleted.
 */
class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = Order::query()
            ->with('user')
            ->withCount('items')
            ->when(is_string($status) && array_key_exists($status, Order::TRANSITIONS), fn ($q) => $q->where('status', $status))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', ['orders' => $orders, 'status' => $status]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['user', 'items.listing', 'histories.changedBy']),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::TRANSITIONS))],
        ]);

        try {
            $result = DB::transaction(function () use ($order, $data, $request) {
                // Re-read under lock so two admins cannot apply conflicting changes.
                $fresh = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

                if (! $fresh->canMoveTo($data['status'])) {
                    return false;
                }

                $from = $fresh->status;
                $fresh->status = $data['status'];
                $fresh->save();
                $fresh->histories()->create([
                    'from_status' => $from,
                    'to_status' => $data['status'],
                    'changed_by' => $request->user()->id,
                ]);

                return true;
            });
        } catch (Throwable $e) {
            Log::error('Order status update failed', ['exception' => $e]);

            return back()->withErrors(['status' => 'The status could not be updated.']);
        }

        if (! $result) {
            return back()->withErrors(['status' => 'That status change is not allowed for this order.']);
        }

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order status updated.');
    }
}

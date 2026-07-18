<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List all orders
    public function index(Request $request)
    {
        $orders = Order::with('items.book', 'payment', 'user')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($sub) use ($search) {
                    $sub->where('order_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('order_date', 'desc')
            ->get();

        $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    // Update order status
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);

        $order = Order::findOrFail($orderId);
        $order->status = $request->status;
        $order->save();
        // The Oracle trigger TRG_AUDIT_ORDER_STATUS automatically logs this change

        return back()->with('success', 'Order #' . $orderId . ' status updated to ' . $request->status);
    }
}
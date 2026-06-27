<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List all orders
    public function index()
    {
        $orders = Order::with('items.book', 'payment')
                        ->orderBy('order_date', 'desc')
                        ->get();

        return view('admin.orders.index', compact('orders'));
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
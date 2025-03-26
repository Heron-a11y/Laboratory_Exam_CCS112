<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // List all orders for employee
    public function index()
    {
        return Order::with('user')->get();
    }

    // Store an order (customer checkout)
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'total_price' => 'required|numeric',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'items' => $request->items,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return response()->json($order, 201);
    }

    // View single order details
    public function show($id)
    {
        return Order::with('user')->findOrFail($id);
    }

    // Update order status (employee)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json($order);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rice;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rice_id' => 'required|exists:rice,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $rice = Rice::findOrFail($request->rice_id);
        
        $total_amount = $request->quantity * $rice->price_per_kg;

        $order = Order::create([
            'rice_id' => $request->rice_id,
            'quantity' => $request->quantity,
            'total_amount' => $total_amount,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'status' => 'Unpaid',
        ]);

        $rice->decrement('stock_qty', $request->quantity);

        return back()->with('success', 'Order created and payment recorded!');
    }
}
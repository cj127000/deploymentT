<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function update(Request $request, $id) {
        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'status' => 'Paid',
            'amount_paid' => $payment->order->total_amount
        ]);

        return back()->with('success', 'Payment processed successfully!');
    }
}
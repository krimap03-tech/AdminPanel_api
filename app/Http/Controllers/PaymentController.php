<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
     public function store(Request $request)
    {
        Payment::create([
            'order_id' => $request->order_id,
            'payment_id' => $request->payment_id,
            'amount' => $request->amount,
            'status' => 'success',
        ]);

        return response()->json(['message' => 'Payment saved successfully']);
    }
}

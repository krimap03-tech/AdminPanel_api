<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // 🔒 Ensure user is authenticated (Sanctum)
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ✅ Validate request
        $validated = $request->validate([
            'movie_id'       => 'required|exists:movies,id',
            'time'           => 'required|string',
            'seats'          => 'required|array|min:1',
            'total'          => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {

            $userId = auth()->id();

            // 1️⃣ Create Booking
            $booking = Booking::create([
                'user_id'        => $userId,
                'movie_id'       => $validated['movie_id'],
                'show_time'      => $validated['time'],
                'seats'          => $validated['seats'],
                'amount'         => $validated['total'],
                'payment_status' => 'paid',
                'payment_method' => $validated['payment_method'] ?? 'upi',
            ]);

            // 2️⃣ Create Payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'order_id'   => 'ORD-' . Str::upper(Str::random(10)),
                'payment_id' => 'PAY-' . Str::upper(Str::random(10)),
                'amount'     => $validated['total'],
                'status'     => 'success',
            ]);

            // 3️⃣ Load movie relation (IMPORTANT)
            $booking->load('movie');

            // 4️⃣ Create Ticket 🎟
            $ticket = Ticket::create([
                'user_id'    => $userId,
                'booking_id' => $booking->id,
                'movie'      => $booking->movie->title, // required by DB
                'date'       => now()->toDateString(), // ✅ REQUIRED
                'time'       => $booking->show_time ?? now()->toTimeString(),   // required by DB
                'seat'       => implode(',', $booking->seats), // required by DB
                'amount'     => $booking->amount,      // required by DB
                'payment_id' => $payment->order_id,    // required by DB
                'ticket_no'  => 'BMS-' . strtoupper(Str::random(8)),
            ]);

            // 5️⃣ Response
            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'ticket' => [
                    'ticket_no'  => $ticket->ticket_no,
                    'movie'      => $booking->movie->title,
                    'movie_id'   => $booking->movie_id,
                    'time'       => $booking->show_time,
                    'seats'      => $booking->seats,
                    'amount'     => $booking->amount,
                    'booking_id' => $booking->id,
                    'booked_at'  => $ticket->created_at->format('d M Y, h:i A'),
                ]
            ], 201);
        });
    }
}

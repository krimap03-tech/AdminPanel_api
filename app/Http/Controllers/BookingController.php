<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Stripe\Stripe;
use Stripe\Checkout\Session;
           

class BookingController extends Controller
{
    // Get all bookings
    public function index()
    {
        return response()->json(Booking::with('Movie')->get());
    }

    // Store a new booking with payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'seats' => 'required|integer|min:1',
        ]);

        // 1️⃣ Create booking (status pending)
        $booking = Booking::create([
            'movie_id' => $validated['movie_id'],
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'seats' => $validated['seats'],
            'status' => 'pending' // new column in bookings table
        ]);

        // 2️⃣ Stripe payment
        stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Booking for Movie ID ' . $booking->movie_id,
                    ],
                    'unit_amount' => 5000 * $booking->seats, // amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/booking-success?booking_id=' . $booking->id,
            'cancel_url' => env('APP_URL') . '/booking-cancel?booking_id=' . $booking->id,
        ]);

        // 3️⃣ Return booking info + payment URL
        return response()->json([
            'booking' => $booking,
            'payment_url' => $session->url
        ], 200);
    }

    // Show single booking
    public function show(Booking $booking)
    {
        return response()->json($booking->load('movie'));
    }

    // Delete a booking
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['message' => 'Booking deleted']);
    }

    // Optional: handle success callback
    public function paymentSuccess(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $booking = Booking::find($bookingId);
        if ($booking) {
            $booking->status = 'confirmed';
            $booking->save();
        }
        return response()->json(['message' => 'Payment successful', 'booking' => $booking]);
    }

    // Optional: handle cancel callback
    public function paymentCancel(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $booking = Booking::find($bookingId);
        if ($booking) {
            $booking->status = 'cancelled';
            $booking->save();
        }
        return response()->json(['message' => 'Payment cancelled', 'booking' => $booking]);
    }
}

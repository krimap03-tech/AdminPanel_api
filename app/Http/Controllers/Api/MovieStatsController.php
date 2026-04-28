<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieStatsController extends Controller
{
    public function movieStats($movieId)
    {
        // Movie details
        $movie = DB::table('movies')->where('id', $movieId)->first();

        if (!$movie) {
            return response()->json([
                'status' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        // Total bookings
        $totalBookings = DB::table('bookings')
            ->where('movie_id', $movieId)
            ->count();

        // Payments related to this movie
        $payments = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.movie_id', $movieId)
            ->select(
                'payments.id',
                'payments.amount',
                'payments.payment_status',
                'payments.payment_method',
                'payments.created_at'
            )
            ->get();

        // Total payment amount
        $totalRevenue = $payments->sum('amount');

        return response()->json([
            'status' => true,
            'movie' => [
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description ?? null,
                'duration' => $movie->duration ?? null
            ],
            'stats' => [
                'total_bookings' => $totalBookings,
                'total_payments' => $payments->count(),
                'total_revenue' => $totalRevenue
            ],
            'payments' => $payments
        ]);
    }


}

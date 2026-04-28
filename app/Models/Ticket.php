<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'movie',
        'date',
        'time',
        'seat',
        'amount',
        'payment_id',
        'ticket_no',
    ];
}

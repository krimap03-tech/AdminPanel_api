<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pospay extends Model
{
    protected $connection = 'pos_cloud';
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'store_id',
        'user_id',
        'amount',
        'method',
        'status',
        'transaction_id'
    ];
}

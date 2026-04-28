<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    protected $connection = 'pos_cloud';
    protected $table = 'stores';

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'phone',
        'status'
    ];
}

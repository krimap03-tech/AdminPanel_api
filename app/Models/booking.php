<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
   
   use HasFactory;

    protected $fillable = [
        'movie_id',
        'user_name',
        'user_email',
        'seats',
        'amount',
        'status',
    ];

    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    
   
}

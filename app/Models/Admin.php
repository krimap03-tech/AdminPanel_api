<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    // 👀 token creation helper
    public function createTokenForAdmin(): string
    {
        return $this->createToken('admin-api-token')->plainTextToken;
    }
}

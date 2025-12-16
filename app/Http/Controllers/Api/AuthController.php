<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Signup / Register
     */
    public function signup(Request $req)
    {
        $req->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6"
        ]);

        $user = User::create([
            "name" => $req->name,
            "email" => $req->email,
            "password" => Hash::make($req->password)
        ]);

        // Create token
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            "user"  => $user,
            "token" => $token,
            "message" => "Signup successful"
        ], 201);
    }

    /**
     * Login
     */
    public function login(Request $req)
    {
        $req->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create token
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            "user"  => $user,
            "token" => $token,
            "message" => "Login successful"
        ], 200);
    }

    /**
     * Logout
     */
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}

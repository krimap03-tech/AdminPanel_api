<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthUiController extends Controller
{
    /**
     * SIGNUP (UI)
     * POST /api/auth/signup
     */
    public function signup(Request $request)
    {
        // 1️⃣ Validate
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // 2️⃣ Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // optional
        ]);

        // 3️⃣ Create Sanctum token
        $token = $user->createToken('Auth_token')->plainTextToken;

        // 4️⃣ Return token to UI
        return response()->json([
            'status' => true,
            'message' => 'Signup successful',
            'token' => $token,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    /**
     * LOGOUT
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}

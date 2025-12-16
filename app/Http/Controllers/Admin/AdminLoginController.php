<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    
     public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Create admin user
        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',   // make sure users table has role column
        ]);

        $token = $admin->createToken('adminToken')->plainTextToken;

        return response()->json([
            'message' => 'Signup successful',
            'token'   => $token,
            'user'    => $admin
        ], 201);
    }
    
  public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
       
    ]);

    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
       
    ];

    $remember = $request->has('remember') && $request->remember;

    if (Auth::attempt($credentials, $remember)) {
        $admin = Auth::user();

        // Revoke old tokens
        $platform = $request->input('platform', 'web');
        $tokenName = $admin->id . '-' . $platform;
        $admin->tokens()->where('name', $tokenName)->delete();

        // Create new token
        $token = $admin->createToken($tokenName);

        // Optional: save FCM token
        if ($request->filled('fcm_token')) {
            $admin->fcm_token = $request->fcm_token;
            $admin->save();
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token->plainTextToken,
            'user' => $admin,
        ]);
    } else {
        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }
}

    public function logout(Request $request)
    {
        $admin = $request->user(); // comes from middleware
        if ($admin) {
            $admin->tokens()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PragmaRX\Google2FAQRCode\Google2FA;


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
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "token" => $token,
            "user"  => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            
            "message" => "Login successful"
        ], 200);
    }

    public function enable2fa()
{
    $google2fa = new Google2FA();

    $secret = $google2fa->generateSecretKey();

    auth()->user()->update([
        'google2fa_secret' => $secret,
        'google2fa_enabled' => true
    ]);

    $qr = $google2fa->getQRCodeInline(
        'YourAppName',
        auth()->user()->email,
        $secret
    );

    return view('2fa.setup', compact('qr', 'secret'));
}
public function verify(Request $request)
{
    $google2fa = new Google2FA();

    $valid = $google2fa->verifyKey(
        auth()->user()->google2fa_secret,
        $request->otp
    );

    if ($valid) {
        session(['2fa_verified' => true]);
        return redirect('/dashboard');
    }

    return back()->withErrors(['otp' => 'Invalid OTP']);
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

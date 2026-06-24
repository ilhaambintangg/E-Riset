<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $admin = Auth::user();
            $token = \Illuminate\Support\Str::random(60);
            $admin->api_token = $token;
            $admin->save();
            
            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'admin' => $admin
            ]);
        }

        throw ValidationException::withMessages([
            'username' => ['Username atau password salah.'],
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        $admin = Auth::user();
        if ($admin) {
            $admin->api_token = null;
            $admin->save();
        }

        Auth::logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Check if admin is logged in and return details.
     */
    public function check()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'admin' => Auth::user()
            ]);
        }

        return response()->json([
            'authenticated' => false
        ], 401);
    }
}

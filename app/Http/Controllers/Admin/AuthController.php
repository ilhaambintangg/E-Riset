<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Admin\Auth\LoginRequest;

class AuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.portal');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Set chat status to online upon successful login
            cache()->forever('admin_chat_status', 'online');
            
            return redirect()->intended('/admin/portal');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $admin = Auth::user();
            $admin->last_seen_at = null;
            $admin->saveQuietly();
        }

        // Set chat status to offline upon logout
        cache()->forever('admin_chat_status', 'offline');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}

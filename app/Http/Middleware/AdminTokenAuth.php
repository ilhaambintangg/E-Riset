<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            $token = $request->input('api_token');
        }

        if (!$token) {
            return response()->json(['message' => 'Unauthorized. Token tidak ditemukan.'], 401);
        }

        $admin = Admin::where('api_token', $token)->first();

        if (!$admin) {
            return response()->json(['message' => 'Unauthorized. Token tidak valid.'], 401);
        }

        Auth::login($admin);

        return $next($request);
    }
}

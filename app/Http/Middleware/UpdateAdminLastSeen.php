<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UpdateAdminLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $admin = Auth::user();
            
            // Set online automatically when admin is active/navigating
            if (cache('admin_chat_status', 'online') === 'offline') {
                cache()->forever('admin_chat_status', 'online');
            }

            // Update last_seen_at if it is null or more than 1 minute ago to reduce DB writes
            if (!$admin->last_seen_at || $admin->last_seen_at->lt(now()->subMinute())) {
                $admin->last_seen_at = now();
                $admin->saveQuietly();
            }
        }

        return $next($request);
    }
}

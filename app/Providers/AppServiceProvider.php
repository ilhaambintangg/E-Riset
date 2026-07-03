<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('layouts.admin', function ($view) {
            $unreadSubmissions = collect();
            $unreadChatsCount = 0;
            $unreadChatMessages = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('submissions')) {
                $unreadSubmissions = \App\Models\Submission::where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('chat_messages')) {
                $unreadChatsCount = \App\Models\ChatMessage::where('sender', 'visitor')
                    ->where('is_read', false)
                    ->count();

                $unreadChatMessages = \App\Models\ChatMessage::where('sender', 'visitor')
                    ->where('is_read', false)
                    ->with('session')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->unique('chat_session_id');
            }

            $view->with([
                'unreadSubmissions' => $unreadSubmissions,
                'unreadChatsCount' => $unreadChatsCount,
                'unreadChatMessages' => $unreadChatMessages,
            ]);
        });
    }
}

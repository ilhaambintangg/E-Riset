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
            if (\Illuminate\Support\Facades\Schema::hasTable('submissions')) {
                $unreadSubmissions = \App\Models\Submission::where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $view->with('unreadSubmissions', $unreadSubmissions);
            } else {
                $view->with('unreadSubmissions', collect());
            }
        });
    }
}

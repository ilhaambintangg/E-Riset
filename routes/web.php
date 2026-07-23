<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicWebController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RequirementController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\PaniteraController;
use App\Http\Controllers\Admin\TemplateSuratController;
use App\Http\Controllers\Admin\GeneratedLetterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\WebSettingController;

// Public Blade Routes
Route::get('/', [PublicWebController::class, 'landing']);
Route::get('/register-permit', [PublicWebController::class, 'form']);
Route::get('/track', [PublicWebController::class, 'track']);
Route::get('/success/{registration_number}', [PublicWebController::class, 'success']);

// Admin Login
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);

// Admin Routes Protected by Session Auth
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\UpdateAdminLastSeen::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Portal, Edvokat & Dashboard (Accessible by both Admin and Hukum roles)
    Route::get('/portal', [\App\Http\Controllers\Admin\Portal\PortalController::class, 'index'])->name('admin.portal');
    Route::get('/edvokat', [\App\Http\Controllers\Admin\Edvokat\EdvokatDashboardController::class, 'index'])->name('admin.edvokat');
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // ADMIN Role Only: Master Data Management
    Route::middleware('role:admin')->group(function () {
        Route::resource('requirements', RequirementController::class);
        Route::resource('faqs', FaqController::class);
        Route::resource('announcements', AnnouncementController::class);
        Route::resource('panitera', PaniteraController::class);
        Route::resource('universities', \App\Http\Controllers\Admin\UniversityController::class);
        Route::resource('hakims', \App\Http\Controllers\Admin\HakimController::class);
        
        // Template Surat
        Route::get('templates', [TemplateSuratController::class, 'index'])->name('templates.index');
        Route::post('templates', [TemplateSuratController::class, 'store'])->name('templates.store');
        Route::post('templates/{id}/activate', [TemplateSuratController::class, 'activate'])->name('templates.activate');
        Route::delete('templates/{id}', [TemplateSuratController::class, 'destroy'])->name('templates.destroy');
        Route::get('templates/{id}/download', [TemplateSuratController::class, 'download'])->name('templates.download');

        // Settings
        Route::get('settings', [WebSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('settings', [WebSettingController::class, 'update'])->name('admin.settings.update');
    });

    // HUKUM Role Only: Submissions Processing, Reports, and Live Chat
    Route::middleware('role:hukum')->group(function () {
        Route::get('/submissions', [AdminDashboardController::class, 'submissions'])->name('admin.submissions.index');
        Route::get('/submissions/{id}', [AdminDashboardController::class, 'submissionDetail'])->name('admin.submissions.show');
        Route::post('/submissions/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.submissions.status');

        // Generated Letter
        Route::post('/submissions/{id}/generate-letter', [GeneratedLetterController::class, 'generate'])->name('admin.submissions.generate');
        Route::get('/submissions/{id}/download-letter', [GeneratedLetterController::class, 'download'])->name('admin.submissions.download');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('reports/export-monthly', [ReportController::class, 'exportMonthly'])->name('admin.reports.exportMonthly');
        Route::get('reports/export-yearly', [ReportController::class, 'exportYearly'])->name('admin.reports.exportYearly');

        // Live Chat Admin Panel
        Route::get('/chats', [\App\Http\Controllers\Admin\Chats\AdminChatsController::class, 'index'])->name('admin.chats.index');
        Route::get('/chats/sessions', [\App\Http\Controllers\Admin\Chats\AdminChatsController::class, 'getSessions'])->name('admin.chats.sessions');
        Route::get('/chats/{id}/messages', [\App\Http\Controllers\Admin\Chats\AdminChatsController::class, 'getMessages'])->name('admin.chats.messages');
        Route::post('/chats/{id}/reply', [\App\Http\Controllers\Admin\Chats\AdminChatsController::class, 'reply'])->name('admin.chats.reply');
        Route::post('/chats/{id}/close', [\App\Http\Controllers\Admin\Chats\AdminChatsController::class, 'closeSession'])->name('admin.chats.close');
    });
});


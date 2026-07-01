<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicWebController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PaniteraController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\GeneratedLetterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WebSettingController;

// Public Blade Routes
Route::get('/', [PublicWebController::class, 'landing']);
Route::get('/register-permit', [PublicWebController::class, 'form']);
Route::get('/track', [PublicWebController::class, 'track']);
Route::get('/success/{registration_number}', [PublicWebController::class, 'success']);

// Admin Login
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);

// Admin Routes Protected by Session Auth
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Portal & Edvokat
    Route::get('/portal', [\App\Http\Controllers\Admin\Portal\PortalController::class, 'index'])->name('admin.portal');
    Route::get('/edvokat', [\App\Http\Controllers\Admin\Edvokat\EdvokatDashboardController::class, 'index'])->name('admin.edvokat');

    // Dashboard & Submissions
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/submissions', [AdminDashboardController::class, 'submissions'])->name('admin.submissions.index');
    Route::get('/submissions/{id}', [AdminDashboardController::class, 'submissionDetail'])->name('admin.submissions.show');
    Route::post('/submissions/{id}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.submissions.status');

    // Generated Letter
    Route::post('/submissions/{id}/generate-letter', [GeneratedLetterController::class, 'generate'])->name('admin.submissions.generate');
    Route::get('/submissions/{id}/download-letter', [GeneratedLetterController::class, 'download'])->name('admin.submissions.download');

    // Master Data
    Route::resource('requirements', RequirementController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('panitera', PaniteraController::class);
    
    // Template Surat
    Route::get('templates', [TemplateSuratController::class, 'index'])->name('templates.index');
    Route::post('templates', [TemplateSuratController::class, 'store'])->name('templates.store');
    Route::delete('templates/{id}', [TemplateSuratController::class, 'destroy'])->name('templates.destroy');
    Route::get('templates/{id}/download', [TemplateSuratController::class, 'download'])->name('templates.download');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/export-monthly', [ReportController::class, 'exportMonthly'])->name('admin.reports.exportMonthly');
    Route::get('reports/export-yearly', [ReportController::class, 'exportYearly'])->name('admin.reports.exportYearly');

    // Settings
    Route::get('settings', [WebSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [WebSettingController::class, 'update'])->name('admin.settings.update');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PermohonanController;

use App\Http\Controllers\PaniteraController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\GeneratedLetterController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    Route::get('requirements', [PublicSubmissionController::class, 'getRequirements']);
    Route::get('faqs', [PublicSubmissionController::class, 'getFaqs']);
    Route::get('announcements', [PublicSubmissionController::class, 'getAnnouncements']);
    Route::post('submissions', [PublicSubmissionController::class, 'store']);
    Route::get('submissions/track/{registration_number}', [PublicSubmissionController::class, 'track']);
    Route::get('submissions/{registration_number}/download-permit', [PublicSubmissionController::class, 'downloadPermit']);

    // Permohonan penelitian sederhana
    Route::post('permohonan', [PermohonanController::class, 'store']);

    // Public Web Settings
    Route::get('settings', [\App\Http\Controllers\WebSettingController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    // Protected admin routes
    Route::middleware(\App\Http\Middleware\AdminTokenAuth::class)->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('check', [AuthController::class, 'check']);

        // Dashboard & Submissions
        Route::get('dashboard', [AdminDashboardController::class, 'getStats']);
        Route::get('submissions', [AdminDashboardController::class, 'getSubmissions']);
        Route::get('submissions/{id}', [AdminDashboardController::class, 'getSubmissionDetail']);
        Route::post('submissions/{id}/status', [AdminDashboardController::class, 'updateStatus']);

        // Generated Letter
        Route::post('submissions/{id}/generate-letter', [GeneratedLetterController::class, 'generate']);
        Route::get('submissions/{id}/download-letter', [GeneratedLetterController::class, 'download']);

        // Reports
        Route::get('reports/monthly', [\App\Http\Controllers\ReportController::class, 'monthly']);
        Route::get('reports/annual', [\App\Http\Controllers\ReportController::class, 'annual']);

        // Web Settings
        Route::get('settings', [\App\Http\Controllers\WebSettingController::class, 'index']);
        Route::put('settings', [\App\Http\Controllers\WebSettingController::class, 'update']);

        // Panitera CRUD
        Route::get('panitera', [PaniteraController::class, 'index']);
        Route::post('panitera', [PaniteraController::class, 'store']);
        Route::get('panitera/{id}', [PaniteraController::class, 'show']);
        Route::put('panitera/{id}', [PaniteraController::class, 'update']);
        Route::delete('panitera/{id}', [PaniteraController::class, 'destroy']);

        // Template Surat
        Route::get('templates', [TemplateSuratController::class, 'index']);
        Route::post('templates', [TemplateSuratController::class, 'store']);
        Route::delete('templates/{id}', [TemplateSuratController::class, 'destroy']);

        // Requirements CRUD
        Route::get('requirements', [RequirementController::class, 'index']);
        Route::post('requirements', [RequirementController::class, 'store']);
        Route::get('requirements/{id}', [RequirementController::class, 'show']);
        Route::put('requirements/{id}', [RequirementController::class, 'update']);
        Route::delete('requirements/{id}', [RequirementController::class, 'destroy']);

        // FAQs CRUD
        Route::get('faqs', [FaqController::class, 'index']);
        Route::post('faqs', [FaqController::class, 'store']);
        Route::get('faqs/{id}', [FaqController::class, 'show']);
        Route::put('faqs/{id}', [FaqController::class, 'update']);
        Route::delete('faqs/{id}', [FaqController::class, 'destroy']);

        // Announcements CRUD
        Route::get('announcements', [AnnouncementController::class, 'index']);
        Route::post('announcements', [AnnouncementController::class, 'store']);
        Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
        Route::put('announcements/{id}', [AnnouncementController::class, 'update']);
        Route::delete('announcements/{id}', [AnnouncementController::class, 'destroy']);
    });
});

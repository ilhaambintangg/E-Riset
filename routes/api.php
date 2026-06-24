<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\PermohonanController;

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

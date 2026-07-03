<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PublicSubmissionController;
use App\Http\Controllers\API\PermohonanController;

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
    Route::get('settings', [\App\Http\Controllers\Admin\WebSettingController::class, 'index']);

    // Live Chat
    Route::get('livechat/status', [\App\Http\Controllers\API\PublicChatController::class, 'status']);
    Route::post('livechat/start', [\App\Http\Controllers\API\PublicChatController::class, 'startSession']);
    Route::get('livechat/messages', [\App\Http\Controllers\API\PublicChatController::class, 'getMessages']);
    Route::post('livechat/send', [\App\Http\Controllers\API\PublicChatController::class, 'sendMessage']);
});

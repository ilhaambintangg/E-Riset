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
    Route::post('livechat/offline-message', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string',
        ]);

        $validated['date'] = now()->translatedFormat('d F Y');
        $validated['time'] = now()->translatedFormat('H:i');

        $adminEmail = 'brodi080600@gmail.com';

        try {
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\OfflineChatMessage($validated));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send Offline Chat Message email: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim. Terima kasih telah menghubungi kami. Pesan Anda telah diteruskan ke Email Admin. Admin akan segera menghubungi Anda.'
        ]);
    });
});

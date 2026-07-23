<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Admin;
use Illuminate\Support\Str;

class PublicChatController extends Controller
{
    /**
     * Start a new chat session.
     */
    public function startSession(\App\Http\Requests\API\LiveChat\StartSessionRequest $request)
    {
        $validated = $request->validated();

        $token = Str::random(40);

        $session = ChatSession::create([
            'session_token' => $token,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'is_active' => true,
        ]);

        // Create an automatic greeting message from system/admin
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'admin',
            'message' => "Halo " . $session->name . "! Selamat datang di layanan Live Chat E-Riset Pengadilan Tinggi Tanjungkarang. Silakan sampaikan pertanyaan atau kendala Anda, admin kami akan segera membantu.",
            'is_read' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'session' => $session
        ]);
    }

    /**
     * Get messages for a session.
     */
    public function getMessages(Request $request)
    {
        $token = $request->header('X-Chat-Token') ?? $request->query('token');

        if (!$token) {
            return response()->json(['error' => 'Token required'], 400);
        }

        $session = ChatSession::where('session_token', $token)->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'is_active' => $session->is_active,
            'messages' => $messages
        ]);
    }

    /**
     * Send a message from the visitor.
     */
    public function sendMessage(\App\Http\Requests\API\LiveChat\SendMessageRequest $request)
    {
        $token = $request->header('X-Chat-Token') ?? $request->input('token');
        
        $validated = $request->validated();

        if (!$token) {
            return response()->json(['error' => 'Token required'], 400);
        }

        $session = ChatSession::where('session_token', $token)->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        if (!$session->is_active) {
            return response()->json(['error' => 'Session is closed'], 400);
        }

        $message = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'visitor',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Check if any admin is online.
     */
    public function status()
    {
        $isHukumOnline = Admin::where('role', 'hukum')
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->exists();

        return response()->json([
            'status' => 'success',
            'admin_online' => $isHukumOnline
        ]);
    }
}


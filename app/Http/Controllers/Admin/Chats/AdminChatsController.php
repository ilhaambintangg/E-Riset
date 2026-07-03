<?php

namespace App\Http\Controllers\Admin\Chats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class AdminChatsController extends Controller
{
    /**
     * Display the Live Chat dashboard.
     */
    public function index(Request $request)
    {
        if ($request->has('session_id')) {
            $sessionId = $request->input('session_id');
            ChatMessage::where('chat_session_id', $sessionId)
                ->where('sender', 'visitor')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('admin.chats.index');
    }

    /**
     * Get list of chat sessions for Admin panel.
     */
    public function getSessions(Request $request)
    {
        $sessions = ChatSession::with(['messages' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])
        ->orderBy('updated_at', 'desc')
        ->get();

        $formatted = $sessions->map(function ($session) {
            $lastMessage = $session->messages->first();
            $unreadCount = $session->messages->where('sender', 'visitor')->where('is_read', false)->count();

            return [
                'id' => $session->id,
                'name' => $session->name,
                'email' => $session->email,
                'is_active' => $session->is_active,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage ? $lastMessage->message : null,
                'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : $session->created_at->diffForHumans(),
                'last_message_timestamp' => $lastMessage ? $lastMessage->created_at->toIso8601String() : $session->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'sessions' => $formatted
        ]);
    }

    /**
     * Get all messages for a specific session & mark them as read.
     */
    public function getMessages($id)
    {
        $session = ChatSession::findOrFail($id);

        // Mark messages from visitor in this session as read
        ChatMessage::where('chat_session_id', $session->id)
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'session' => $session,
            'messages' => $messages
        ]);
    }

    /**
     * Send a reply from admin.
     */
    public function reply(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        if (!$session->is_active) {
            return response()->json(['error' => 'Sesi chat sudah ditutup'], 400);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'admin',
            'message' => $validated['message'],
            'is_read' => true,
            'changed_by_admin_id' => auth()->id(),
        ]);

        // Touch the session to update updated_at for sorting
        $session->touch();

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Close/end the chat session.
     */
    public function closeSession($id)
    {
        $session = ChatSession::findOrFail($id);
        $session->is_active = false;
        $session->save();

        $message = ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'admin',
            'message' => 'Sesi percakapan ini telah diakhiri oleh Administrator.',
            'is_read' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }
}

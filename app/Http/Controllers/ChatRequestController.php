<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatRequestController extends Controller
{
    /**
     * 📤 Send chat request
     */
    public function sendRequest(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|integer',
            'receiver_type' => 'required|string|in:user,trainer,admin',
        ]);

        $sender = Auth::user();

        // 🧠 Determine sender type
        $senderType = $sender->is_admin ? 'admin' : 'user';

        // 🚫 Prevent sending to *exact same account* (same id and same type)
        if ($sender->id == $validated['receiver_id'] && $senderType === $validated['receiver_type']) {
            return back()->with('error', 'You cannot send a chat request to yourself.');
        }

        // ✅ Allow same email if type is different (user vs trainer)
        // So no email comparison here at all.

        // 🔍 Check for existing request (in either direction, with type considered)
        $exists = ChatRequest::where(function ($q) use ($sender, $validated, $senderType) {
            $q->where('sender_id', $sender->id)
              ->where('receiver_id', $validated['receiver_id'])
              ->where('sender_type', $senderType)
              ->where('receiver_type', $validated['receiver_type']);
        })->orWhere(function ($q) use ($sender, $validated, $senderType) {
            $q->where('sender_id', $validated['receiver_id'])
              ->where('receiver_id', $sender->id)
              ->where('sender_type', $validated['receiver_type'])
              ->where('receiver_type', $senderType);
        })->first();

        if ($exists) {
            return back()->with('info', 'Chat request already exists.');
        }

        // 💾 Store new chat request
        ChatRequest::create([
            'sender_id' => $sender->id,
            'sender_type' => $senderType,
            'receiver_id' => $validated['receiver_id'],
            'receiver_type' => $validated['receiver_type'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Chat request sent successfully!');
    }

    /**
     * ✅ Accept chat request & create chat room (Node.js MongoDB)
     */
    public function acceptRequest($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        // 🧠 Identify correct logged-in guard
        $authId = auth()->guard('trainer')->check() 
            ? auth()->guard('trainer')->id()
            : auth()->id();

        \Log::debug('💬 Accept Request Debug', [
            'authId' => $authId,
            'receiver_id' => $chatRequest->receiver_id,
            'sender_id' => $chatRequest->sender_id,
            'status' => $chatRequest->status,
            'sender_type' => $chatRequest->sender_type,
            'receiver_type' => $chatRequest->receiver_type,
        ]);

        // 🧠 Ensure only receiver can accept
        if ($chatRequest->receiver_id !== $authId) {
            \Log::warning('❌ Unauthorized accept attempt', [
                'chatRequestId' => $chatRequest->id,
                'authId' => $authId,
                'receiver_id' => $chatRequest->receiver_id,
            ]);
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // ✅ Update status
        $chatRequest->update(['status' => 'accepted']);

        // 🌐 Call Node.js to create chat room
        $nodeServer = env('NODE_SERVER_URL', 'http://localhost:4000');
        try {
            $response = Http::post("$nodeServer/api/create-room", [
                'participants' => [
                    ['type' => $chatRequest->sender_type, 'id' => $chatRequest->sender_id],
                    ['type' => $chatRequest->receiver_type, 'id' => $chatRequest->receiver_id],
                ],
            ]);

            if ($response->failed()) {
                \Log::error('❌ Node.js room creation failed', ['response' => $response->body()]);
                return back()->with('error', 'Failed to create chat room.');
            }

            $roomData = $response->json();
            \Log::info('✅ Chat request accepted successfully', ['roomData' => $roomData]);

            return back()->with('success', 'Chat request accepted successfully.');

        } catch (\Exception $e) {
            \Log::error('🔥 Error connecting to chat server', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error connecting to chat server.');
        }
    }


    /**
     * 📋 Get all pending requests for the logged-in user
     */
    public function myRequests()
    {
        $user = Auth::user();
        $userType = $user->is_admin ? 'admin' : 'user';

        $requests = ChatRequest::where('receiver_id', $user->id)
            ->where('receiver_type', $userType)
            ->where('status', 'pending')
            ->get();

        return response()->json($requests);
    }
     /**
     * ❌ Decline chat request
     */
    public function declineRequest($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        // ✅ Only receiver can decline
        if ($chatRequest->receiver_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // 🗑 Option 1: Delete the record
        $chatRequest->update(['status' => 'declined']);

        // 🟡 Option 2 (alternative): Just mark declined instead of delete
        // $chatRequest->update(['status' => 'declined']);

        return back()->with('success', 'Chat request declined successfully.');
    }
}

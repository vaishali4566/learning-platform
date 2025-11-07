<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatRequestController extends Controller
{
    /**
     * Send chat request
     */
    public function sendRequest(Request $request)
    {
        Log::info('sendRequest() Called', [
            'request_data' => $request->all(),
            'user_guard' => auth('trainer')->check() ? 'trainer' : 'user',
            'auth_user' => auth('trainer')->check() ? auth('trainer')->user()?->toArray() : auth()->user()?->toArray(),
        ]);

        $validated = $request->validate([
            'receiver_id' => 'required|integer',
            'receiver_type' => 'required|string|in:user,trainer,admin',
        ]);

        // SAHI SENDER NIKAL
        if (auth('trainer')->check()) {
            $sender = auth('trainer')->user();
            $senderType = 'trainer';
        } else {
            $sender = auth()->user();
            $senderType = $sender->is_admin ? 'admin' : 'user';
        }

        if (!$sender) {
            Log::error('Sender not authenticated');
            return back()->with('error', 'Unauthorized.');
        }

        Log::info('Sender Info', [
            'sender_id' => $sender->id,
            'sender_type' => $senderType,
            'receiver_id' => $validated['receiver_id'],
            'receiver_type' => $validated['receiver_type'],
        ]);

        // Prevent self-request
        if ($sender->id == $validated['receiver_id'] && $senderType === $validated['receiver_type']) {
            Log::warning('Self-request blocked');
            return back()->with('error', 'You cannot send a chat request to yourself.');
        }

        // Check existing request
        $exists = ChatRequest::where(function ($q) use ($sender, $validated, $senderType) {
            $q->where('sender_id', $sender->id)
              ->where('sender_type', $senderType)
              ->where('receiver_id', $validated['receiver_id'])
              ->where('receiver_type', $validated['receiver_type']);
        })->orWhere(function ($q) use ($sender, $validated, $senderType) {
            $q->where('sender_id', $validated['receiver_id'])
              ->where('sender_type', $validated['receiver_type'])
              ->where('receiver_id', $sender->id)
              ->where('receiver_type', $senderType);
        })->first();

        if ($exists) {
            Log::info('Request already exists', ['chat_request_id' => $exists->id]);
            return back()->with('info', 'Chat request already exists.');
        }

        $chatRequest = ChatRequest::create([
            'sender_id' => $sender->id,
            'sender_type' => $senderType,
            'receiver_id' => $validated['receiver_id'],
            'receiver_type' => $validated['receiver_type'],
            'status' => 'pending',
        ]);

        Log::info('NEW CHAT REQUEST CREATED', [
            'id' => $chatRequest->id,
            'sender' => $senderType . '_' . $sender->id,
            'receiver' => $validated['receiver_type'] . '_' . $validated['receiver_id'],
        ]);

        return back()->with('success', 'Chat request sent successfully!');
    }

    /**
     * Accept chat request
     */
    public function acceptRequest($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        $authId = auth('trainer')->check() ? auth('trainer')->id() : auth()->id();
        $authType = auth('trainer')->check() ? 'trainer' : (auth()->user()?->is_admin ? 'admin' : 'user');

        Log::debug('acceptRequest() Called', [
            'chat_request_id' => $id,
            'authId' => $authId,
            'authType' => $authType,
            'receiver_id' => $chatRequest->receiver_id,
            'receiver_type' => $chatRequest->receiver_type,
        ]);

        if ($chatRequest->receiver_id !== $authId || $chatRequest->receiver_type !== $authType) {
            Log::warning('Unauthorized accept attempt', [
                'chat_request_id' => $id,
                'authId' => $authId,
                'authType' => $authType,
            ]);
            return back()->with('error', 'Unauthorized access.');
        }

        $chatRequest->update(['status' => 'accepted']);
        Log::info('Chat request accepted', ['id' => $chatRequest->id]);

        $nodeServer = env('NODE_SERVER_URL', 'http://localhost:4000');
        try {
            $response = Http::post("$nodeServer/api/create-room", [
                'participants' => [
                    ['type' => $chatRequest->sender_type, 'id' => $chatRequest->sender_id],
                    ['type' => $chatRequest->receiver_type, 'id' => $chatRequest->receiver_id],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Node.js room creation failed', ['response' => $response->body()]);
                return back()->with('error', 'Failed to create chat room.');
            }

            Log::info('Node.js room created', ['response' => $response->json()]);
            return back()->with('success', 'Chat request accepted!');
        } catch (\Exception $e) {
            Log::error('Chat server error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Server error.');
        }
    }

    /**
     * Decline chat request
     */
    public function declineRequest($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        $authId = auth('trainer')->check() ? auth('trainer')->id() : auth()->id();
        $authType = auth('trainer')->check() ? 'trainer' : (auth()->user()?->is_admin ? 'admin' : 'user');

        Log::debug('declineRequest() Called', [
            'chat_request_id' => $id,
            'authId' => $authId,
            'authType' => $authType,
        ]);

        if ($chatRequest->receiver_id !== $authId || $chatRequest->receiver_type !== $authType) {
            Log::warning('Unauthorized decline attempt');
            return back()->with('error', 'Unauthorized.');
        }

        $chatRequest->update(['status' => 'declined']);
        Log::info('Chat request declined', ['id' => $chatRequest->id]);
        return back()->with('success', 'Chat request declined.');
    }

    /**
     * Get pending requests (for AJAX)
     */
    public function myRequests()
    {
        $authId = auth('trainer')->check() ? auth('trainer')->id() : auth()->id();
        $authType = auth('trainer')->check() ? 'trainer' : (auth()->user()?->is_admin ? 'admin' : 'user');

        $requests = ChatRequest::where('receiver_id', $authId)
            ->where('receiver_type', $authType)
            ->where('status', 'pending')
            ->get();

        Log::info('myRequests() Called', [
            'authId' => $authId,
            'authType' => $authType,
            'count' => $requests->count(),
            'requests' => $requests->toArray(),
        ]);

        return response()->json($requests);
    }
}
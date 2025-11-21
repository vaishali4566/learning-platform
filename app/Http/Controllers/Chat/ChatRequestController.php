<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatRequestController extends Controller
{
    

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
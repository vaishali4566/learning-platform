<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trainer;
use App\Models\ChatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ✅ Identify which guard is active (user, trainer, or admin)
    // ✅ Identify which guard is active (user, trainer, or admin)
    private function getAuthInfo()
    {
        // Try to get logged-in user or trainer
        $user = Auth::guard('web')->user() ?? Auth::guard('trainer')->user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        // Detect type based on guard and is_admin flag
        $type = 'user';
        if (Auth::guard('trainer')->check()) {
            $type = 'trainer';
        } elseif (isset($user->is_admin) && $user->is_admin) {
            $type = 'admin';
        }

        return [
            'model' => $user,
            'type' => $type,
        ];
    }


    // ✅ Chat list page
    public function index()
    {
        $auth = $this->getAuthInfo();
        $authUser = $auth['model'];
        $authType = $auth['type'];

        // Fetch users and trainers
        $users = User::where('id', '!=', $authUser->id)->get(['id', 'name', 'email', 'is_admin']);
        $trainers = Trainer::select('id', 'name', 'email')->get();

        // Merge and tag user roles
        $allUsers = $users->map(function ($user) {
            $user->role = $user->is_admin ? 'Admin' : 'User';
            $user->type = $user->is_admin ? 'admin' : 'user';
            return $user;
        })->merge(
            $trainers->map(function ($trainer) {
                $trainer->role = 'Trainer';
                $trainer->type = 'trainer';
                return $trainer;
            })
        )->sortBy('name')->values();

        // Fetch chat requests
        $chatRequests = ChatRequest::where(function ($q) use ($authUser, $authType) {
            $q->where('sender_id', $authUser->id)
              ->where('sender_type', $authType);
        })->orWhere(function ($q) use ($authUser, $authType) {
            $q->where('receiver_id', $authUser->id)
              ->where('receiver_type', $authType);
        })->get();

        $chatRequestsByUser = [];
        foreach ($chatRequests as $req) {
            $otherId = $req->sender_id == $authUser->id ? $req->receiver_id : $req->sender_id;
            $chatRequestsByUser[$otherId] = $req;
        }

        return view('chat.index', [
            'users' => $allUsers,
            'chatRequests' => $chatRequestsByUser,
        ]);
    }

    // ✅ Send chat request
    public function sendRequest(Request $request, $id)
    {
        $auth = $this->getAuthInfo();
        $sender = $auth['model'];
        $senderType = $auth['type'];
        $receiverType = $request->input('receiver_type', 'user');
        $receiverId = $id;

        if ($sender->id == $receiverId && $senderType == $receiverType) {
            return back()->with('error', 'You cannot send a request to yourself.');
        }

        // Check if chat request already exists
        $existing = ChatRequest::where(function ($q) use ($sender, $senderType, $receiverId, $receiverType) {
            $q->where('sender_id', $sender->id)
              ->where('sender_type', $senderType)
              ->where('receiver_id', $receiverId)
              ->where('receiver_type', $receiverType);
        })->orWhere(function ($q) use ($sender, $senderType, $receiverId, $receiverType) {
            $q->where('sender_id', $receiverId)
              ->where('sender_type', $receiverType)
              ->where('receiver_id', $sender->id)
              ->where('receiver_type', $senderType);
        })->first();

        if ($existing) {
            return back()->with('info', 'Chat request already exists.');
        }

        ChatRequest::create([
            'sender_id' => $sender->id,
            'sender_type' => $senderType,
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'status' => 'pending',
        ]);
        return back()->with('success', 'Chat request sent successfully!');
    }

    // ✅ Accept chat request
    public function acceptRequest($id)
    {
        $auth = $this->getAuthInfo();
        $receiver = $auth['model'];
        $receiverType = $auth['type'];

        $chatRequest = ChatRequest::findOrFail($id);
        if ($chatRequest->receiver_id != $receiver->id || $chatRequest->receiver_type != $receiverType) {
            return back()->with('error', 'Unauthorized action.');
        }

        $chatRequest->status = 'accepted';
        $chatRequest->save();

        // ✅ Create or find chat room on Node.js server
        $nodeServer = env('NODE_SERVER_URL', 'http://127.0.0.1:4000');

        $response = Http::post("$nodeServer/api/create-room", [
            'participants' => [
                ['type' => $chatRequest->sender_type, 'id' => (int) $chatRequest->sender_id],
                ['type' => $chatRequest->receiver_type, 'id' => (int) $chatRequest->receiver_id],
            ],
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to create chat room on Node server.');
        }

        $room = $response->json('room');
        $roomId = $room['_id'] ?? null;

        // ✅ Store room ID in the chat request (optional but useful for reusing)
        $chatRequest->room_id = $roomId;
        $chatRequest->save();

        return back()->with('success', 'Chat request accepted and chat room created!');
    }

    // ✅ Chat room page
    public function room($id)
    {
        $auth = $this->getAuthInfo();
        $authUser = $auth['model'];
        $authType = $auth['type'];

        $receiver = User::find($id) ?? Trainer::find($id);
        if (!$receiver) {
            return back()->with('error', 'User not found.');
        }

        $receiverType = $receiver instanceof Trainer ? 'trainer' : ($receiver->is_admin ? 'admin' : 'user');

        $nodeServer = env('NODE_SERVER_URL', 'http://127.0.0.1:4000');

        // ✅ Always create/find room on Node server
        $response = Http::post("$nodeServer/api/create-room", [
            'participants' => [
                ['type' => $authType, 'id' => (int) $authUser->id],
                ['type' => $receiverType, 'id' => (int) $receiver->id],
            ],
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to create or join chat room.');
        }

        $room = $response->json('room');
        $room_id = $room['_id'] ?? $room['id'] ?? null;

        return view('chat.room', compact('receiver', 'room_id'));
    }
}
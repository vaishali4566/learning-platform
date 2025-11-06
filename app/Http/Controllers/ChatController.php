<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

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
    // 🔹 Detect current logged-in user
    if (auth()->guard('trainer')->check()) {
        $authUser = auth()->guard('trainer')->user();
        $authType = 'trainer';
    } elseif (auth()->check()) {
        $authUser = auth()->user();
        $authType = $authUser->is_admin ? 'admin' : 'user';
    } else {
        abort(403, 'Unauthorized');
    }

    // 🧩 Debug 1: Log current logged-in user
    Log::info('🔹 Logged In User', [
        'id' => $authUser->id,
        'name' => $authUser->name,
        'email' => $authUser->email,
        'type' => $authType
    ]);

    // 🔹 Fetch all users (Admin + Normal Users)
    $users = User::get(['id', 'name', 'email', 'is_admin'])
        ->map(function ($user) {
            $user->role = $user->is_admin ? 'Admin' : 'User';
            $user->type = $user->is_admin ? 'admin' : 'user';
            $user->unique_key = 'user_' . $user->id;
            return $user;
        });

    // 🧩 Debug 2: Log users fetched
    Log::info('📋 All Users', $users->map(fn($u) => [
        'id' => $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'type' => $u->type
    ])->toArray());

    // 🔹 Fetch all trainers
    $trainers = Trainer::get(['id', 'name', 'email'])
        ->map(function ($trainer) {
            $trainer->role = 'Trainer';
            $trainer->type = 'trainer';
            $trainer->unique_key = 'trainer_' . $trainer->id;
            return $trainer;
        });

    // 🧩 Debug 3: Log trainers fetched
    Log::info('🎓 All Trainers', $trainers->map(fn($t) => [
        'id' => $t->id,
        'name' => $t->name,
        'email' => $t->email,
        'type' => $t->type
    ])->toArray());

    // 🔹 Merge all (users + trainers)
    $allUsers = $users->concat($trainers)
        ->filter(function ($user) use ($authUser, $authType) {
            // exclude self only if same type and same id
            return !(($authType === $user->type) && ($authUser->id === $user->id));
        })
        ->sortBy('name')
        ->values();

    // 🧩 Debug 4: Log merged users
    Log::info('👥 Merged Users (Final List)', $allUsers->map(fn($u) => [
        'id' => $u->id,
        'name' => $u->name,
        'email' => $u->email,
        'type' => $u->type,
    ])->toArray());

    // 🔹 Fetch chat requests related to logged-in user
    $chatRequests = ChatRequest::where(function ($q) use ($authUser, $authType) {
        $q->where('sender_id', $authUser->id)
          ->where('sender_type', $authType);
    })->orWhere(function ($q) use ($authUser, $authType) {
        $q->where('receiver_id', $authUser->id)
          ->where('receiver_type', $authType);
    })->get();

    // 🔹 Map chat requests by unique key
    $chatRequestsByUser = [];
    foreach ($chatRequests as $req) {
        $otherId = $req->sender_id == $authUser->id ? $req->receiver_id : $req->sender_id;
        $otherType = $req->sender_id == $authUser->id ? $req->receiver_type : $req->sender_type;
        $key = $otherType . '_' . $otherId;
        $chatRequestsByUser[$key] = $req;
    }

    // 🧩 Debug 5: Log chat requests
    Log::info('💬 Chat Requests', $chatRequestsByUser);

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
    public function room(Request $request, $id)
    {
        $auth = $this->getAuthInfo();
        $authUser = $auth['model'];
        $authType = $auth['type'];

        // 🔹 Determine receiver type from query string (e.g., ?type=trainer)
        $type = $request->query('type', 'user');

        // 🔹 Fetch correct receiver based on type
        if ($type === 'trainer') {
            $receiver = Trainer::find($id);
        } elseif ($type === 'admin') {
            $receiver = User::where('id', $id)->where('is_admin', 1)->first();
        } else {
            $receiver = User::where('id', $id)->where('is_admin', 0)->first();
        }

        if (!$receiver) {
            return back()->with('error', 'Receiver not found.');
        }

        // 🔹 Set receiver type
        $receiverType = $type;

        // 🧩 Debugging log
        Log::info('Chat Room Receiver', [
            'auth_id' => $authUser->id,
            'auth_type' => $authType,
            'requested_type' => $type,
            'receiver_class' => get_class($receiver),
            'receiver_id' => $receiver->id,
            'receiverType' => $receiverType,
        ]);

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
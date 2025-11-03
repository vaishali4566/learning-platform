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
    public function index()
    {
        $authUser = Auth::user();

        // Fetch users and trainers
        $users = User::where('id', '!=', $authUser->id)->get(['id', 'name', 'email', 'is_admin']);
        $trainers = Trainer::select('id', 'name', 'email')->get();

        // Merge + Tag
        $allUsers = $users->map(function ($user) {
            $user->role = $user->is_admin ? 'Admin' : 'User';
            $user->type = 'user';
            return $user;
        })->merge(
            $trainers->map(function ($trainer) {
                $trainer->role = 'Trainer';
                $trainer->type = 'trainer';
                return $trainer;
            })
        )->sortBy('name')->values();

        // Get chat requests
        $chatRequests = ChatRequest::where('sender_id', $authUser->id)
            ->orWhere('receiver_id', $authUser->id)
            ->get();

        $chatRequestsByUser = [];
        foreach ($chatRequests as $req) {
            $otherUserId = $req->sender_id == $authUser->id ? $req->receiver_id : $req->sender_id;
            $chatRequestsByUser[$otherUserId] = $req;
        }

        return view('user.chat.index', [
            'users' => $allUsers,
            'chatRequests' => $chatRequestsByUser,
        ]);
    }

    // ✅ Send Request
    public function sendRequest(Request $request, $id)
    {
        $senderId = Auth::id();
        $receiverId = $id;
        $senderType = 'user';
        $receiverType = $request->input('receiver_type', 'user');

        if ($senderId == $receiverId) {
            return back()->with('error', 'You cannot send a request to yourself.');
        }

        $existing = ChatRequest::where(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->first();

        if ($existing) {
            return back()->with('info', 'Chat request already exists.');
        }

        ChatRequest::create([
            'sender_id' => $senderId,
            'sender_type' => $senderType,
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Chat request sent successfully!');
    }

    // ✅ Accept Request
    public function acceptRequest($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        if ($chatRequest->receiver_id != Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $chatRequest->status = 'accepted';
        $chatRequest->save();

        return back()->with('success', 'Chat request accepted successfully!');
    }

    // ✅ Chat Room Page
    public function room($id)
    {
        $authUser = Auth::user();
        $receiver = User::findOrFail($id);

        // Call Node.js to create/find room
        $response = Http::post('http://localhost:4000/api/create-room', [
            'participants' => [
                [ 'type' => 'user', 'id' => (int) $authUser->id ],
                [ 'type' => 'user', 'id' => (int) $receiver->id ],
            ],
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to create chat room.');
        }

        $room = $response->json('room');
        $room_id = $room['_id'] ?? null;

        return view('user.chat.room', compact('receiver', 'room_id'));
    }
}

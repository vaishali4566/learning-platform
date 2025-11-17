@php
use App\Models\User;
use App\Models\Trainer;

// 🔹 Detect which guard is active
if (auth()->guard('trainer')->check()) {
    $layout = 'layouts.trainer.index';
    $authType = 'trainer';
    $authId = auth()->guard('trainer')->id();
} elseif (auth()->check() && auth()->user()->is_admin) {
    $layout = 'layouts.admin.index';
    $authType = 'admin';
    $authId = auth()->id();
} elseif (auth()->check()) {
    $layout = 'layouts.user.index';
    $authType = 'user';
    $authId = auth()->id();
} else {
    $layout = 'layouts.user.index';
    $authType = 'guest';
    $authId = null;
}

// ✅ Detect receiver type safely
if ($receiver instanceof Trainer) {
    $receiverType = 'trainer';
} elseif ($receiver instanceof User && !empty($receiver->is_admin) && $receiver->is_admin == 1) {
    $receiverType = 'admin';
} elseif ($receiver instanceof User) {
    $receiverType = 'user';
} else {
    $receiverType = 'user';
}
@endphp

@extends($layout)
@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-[#0D1117] rounded-2xl shadow-2xl border border-[#1F2937] overflow-hidden flex flex-col h-[85vh]">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 bg-[#161B22] border-b border-[#1F2937]">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white font-semibold text-lg">
                {{ strtoupper(substr($receiver->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-100">{{ $receiver->name }}</h2>
                <p id="user-status" class="text-xs text-gray-400">Offline</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button id="unfriendBtn" class="text-red-500 font-semibold hover:text-red-700 transition-all">
                Unfriend
            </button>
            <div class="text-gray-400 text-sm">Chat Room.{{ $room_id }}</div>
        </div>
    </div>

    {{-- Messages --}}
    <div id="chat-box"
         data-room-id="{{ $room_id ?? '' }}"
         data-user-id="{{ Auth::guard('trainer')->check() ? Auth::guard('trainer')->id() : Auth::id() }}"
         data-user-type="{{ Auth::guard('trainer')->check() ? 'trainer' : (Auth::user() && Auth::user()->is_admin ? 'admin' : 'user') }}"
         data-receiver-id="{{ $receiver->id }}"
         data-receiver-type="{{ $receiverType }}"
         class="flex-1 overflow-y-auto px-6 py-4 space-y-3 bg-[#0D1117] custom-scrollbar">
    </div>

    {{-- Input --}}
    <form id="message-form" class="border-t border-[#1F2937] bg-[#161B22] flex items-center px-4 py-3 gap-2">
        <label for="file-input" class="cursor-pointer bg-[#0D1117] border border-[#2D3748] rounded-xl p-2.5 hover:bg-[#1E293B] transition-all">Attachment</label>
        <input type="file" id="file-input" class="hidden" accept="image/*,.pdf,.doc,.docx,.zip" />

        <input type="text" id="message-input" placeholder="Type your message..." class="flex-grow bg-[#0D1117] text-gray-200 placeholder-gray-500 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600">

        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-5 py-2.5 rounded-xl transition-all duration-200 shadow-md">
            Send
        </button>
    </form>
</div>

@vite(['resources/js/chat.js'])

<style>
.custom-scrollbar::-webkit-scrollbar { width: 8px; }
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #3b82f6, #6366f1);
  border-radius: 9999px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #2563eb, #4f46e5);
}
.custom-scrollbar { scrollbar-width: thin; scrollbar-color: #3b82f6 transparent; }
</style>

<script type="module">
document.addEventListener("DOMContentLoaded", async () => {
    const chatBox = document.getElementById("chat-box");
    const form = document.getElementById("message-form");
    const input = document.getElementById("message-input");
    const fileInput = document.getElementById("file-input");
    const unfriendBtn = document.getElementById("unfriendBtn");

    const roomId = String(chatBox.dataset.roomId || '');
    const userId = parseInt(chatBox.dataset.userId);
    const userType = chatBox.dataset.userType;
    const receiverId = parseInt(chatBox.dataset.receiverId);
    const receiverType = chatBox.dataset.receiverType;

    window.currentRoomId = roomId;
    window.currentReceiver = { id: receiverId, type: receiverType };

    // ✅ Socket Join Room
    if (!window.socket) return console.error("Socket not loaded");
    const joinRoom = () => window.socket.emit("join", { roomId });
    if (window.socket.connected) joinRoom();
    else window.socket.once("connect", joinRoom);

    // ✅ Load old messages
    try {
        const res = await fetch(`http://127.0.0.1:4000/api/messages/${roomId}`);
        const data = await res.json();
        if (data?.messages?.length) {
            data.messages.forEach(msg => window.renderMessage(msg, userId, userType, "api"));
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    } catch (err) {
        console.error("Load failed:", err);
    }

    // ✅ Auto-send file
    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (!file) return;
        window.sendMessage(roomId, userId, userType, receiverId, receiverType, "", file);
        fileInput.value = "";
    });

    // ✅ Send message
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;
        window.sendMessage(roomId, userId, userType, receiverId, receiverType, message);
        input.value = "";
    });

    // ✅ Unfriend Logic
   unfriendBtn.addEventListener("click", async () => {
    const confirmUnfriend = confirm("Are you sure you want to unfriend?");
    if (!confirmUnfriend) return;

    try {
        const response = await fetch(`/chat/unfriend`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                sender_id: window.currentUser.id,
                sender_type: window.currentUser.type,
                receiver_id: window.currentReceiver.id,
                receiver_type: window.currentReceiver.type,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            alert("Unfriended successfully!");

            // 🔥 Node ko event send karo
            fetch("http://127.0.0.1:4000/api/unfriend-event", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    sender_id: window.currentUser.id,
                    sender_type: window.currentUser.type,
                    receiver_id: window.currentReceiver.id,
                    receiver_type: window.currentReceiver.type,
                }),
            });

            window.location.href = "/chat";
        } else {
            alert(data.message || "Failed to unfriend.");
        }
    } catch (err) {
        console.error("Unfriend error:", err);
        alert("Something went wrong.");
    }
});


});
</script>
@endsection

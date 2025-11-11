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

// 🔍 Debugging info
\Log::info('Chat Debug', [
    'auth_id' => $authId,
    'auth_type' => $authType,
    'receiver_class' => get_class($receiver),
    'receiver_attrs' => $receiver->toArray(),
    'detected_receiver_type' => $receiverType,
]);
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
        <div class="text-gray-400 text-sm">Chat Room.{{ $room_id }}</div>
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

    const roomId = String(chatBox.dataset.roomId || '');
    window.currentRoomId = roomId
    const userId = parseInt(chatBox.dataset.userId);
    const userType = chatBox.dataset.userType;
    const receiverId = parseInt(chatBox.dataset.receiverId);
    const receiverType = chatBox.dataset.receiverType;

    if (!window.socket) return console.error("Socket not loaded");

    // Join room
    const joinRoom = () => window.socket.emit("join", { roomId });
    if (window.socket.connected) joinRoom();
    else window.socket.once("connect", joinRoom);

    // Load old messages
    try {
        const res = await fetch(`http://127.0.0.1:4000/api/messages/${roomId}`);
        const data = await res.json();
        if (data?.messages?.length) {
            data.messages.forEach(msg => window.renderMessage(msg, userId, userType, "api")); // ✅ FIXED
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    } catch (err) {
        console.error("Load failed:", err);
    }


    // AUTO SEND FILE ON SELECT
    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (!file) return;
        window.sendMessage(roomId, userId, userType, receiverId, receiverType, "", file);
        fileInput.value = "";
    });

    // SEND TEXT ONLY
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;
        window.sendMessage(roomId, userId, userType, receiverId, receiverType, message);
        input.value = "";
    });
});
</script>
@endsection
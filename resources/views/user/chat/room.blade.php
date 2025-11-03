@extends('layouts.user.index')

@section('title', 'Chat Room')

@section('content')
<div class="max-w-3xl mx-auto bg-gray-800 rounded-xl shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Chat with {{ $receiver->name }}</h2>

    <div id="chat-box"
         data-room-id="{{ $room_id }}"
         data-user-id="{{ Auth::id() }}"
         data-user-type="user"
         data-receiver-id="{{ $receiver->id }}"
         data-receiver-type="trainer"
         class="h-80 overflow-y-auto bg-gray-900 p-4 rounded mb-4 flex flex-col space-y-1">
    </div>

    <form id="message-form" class="flex gap-2">
        <input type="text" id="message-input" placeholder="Type message..."
               class="flex-grow p-2 rounded bg-gray-700 text-white">
        <button type="submit" class="bg-blue-600 px-4 py-2 rounded">Send</button>
    </form>
</div>

{{-- ✅ Load the chat script --}}
@vite(['resources/js/chat.js'])

<script type="module">
document.addEventListener("DOMContentLoaded", async () => {
    const chatBox = document.getElementById("chat-box");
    const roomId = chatBox.dataset.roomId;
    const userId = parseInt(chatBox.dataset.userId);
    const userType = chatBox.dataset.userType;
    const receiverId = parseInt(chatBox.dataset.receiverId);
    const receiverType = chatBox.dataset.receiverType;

    // ✅ Join room once
    window.socket.emit("join", { roomId });
    console.log("✅ Joined room:", roomId);

    // ✅ Load old messages
    try {
        const res = await fetch(`http://127.0.0.1:4000/api/messages/${roomId}`);
        const data = await res.json();
        if (data?.messages?.length) {
            data.messages.forEach((msg) => {
                const div = document.createElement("div");
                const isMine = String(msg.sender.id) === String(userId) && msg.sender.type === userType;

                div.classList.add(
                    "p-2", "rounded", "mb-1", "max-w-[80%]",
                    isMine
                        ? "bg-blue-600 text-white self-end ml-auto text-right"
                        : "bg-gray-700 text-white text-left"
                );
                div.textContent = msg.message;
                chatBox.appendChild(div);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    } catch (err) {
        console.error("❌ Error loading messages:", err);
    }

    // ✅ Handle form submit
    const form = document.getElementById("message-form");
    const input = document.getElementById("message-input");

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        // show instantly
        const div = document.createElement("div");
        div.classList.add("p-2", "rounded", "mb-1", "bg-blue-600", "text-white", "text-right", "ml-auto");
        div.textContent = message;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;

        // send to backend
        window.sendMessage(roomId, userId, userType, receiverId, receiverType, message);
        input.value = "";
    });
});
</script>
@endsection

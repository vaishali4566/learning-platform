// ✅ resources/js/chat.js
import { io } from "socket.io-client";

export const socket = io("http://127.0.0.1:4000", { transports: ["websocket"] });

socket.on("connect", () => console.log("✅ Connected:", socket.id));
socket.on("disconnect", () => console.log("❌ Disconnected"));

// Listen for new messages
socket.on("newMessage", (msg) => {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const currentRoomId = chatBox.dataset.roomId;
  if (String(msg.roomId) !== String(currentRoomId)) return;

  const userId = parseInt(chatBox.dataset.userId);
  const userType = chatBox.dataset.userType;

  const isMine = msg.sender.id == userId && msg.sender.type == userType;

  const div = document.createElement("div");
  div.classList.add(
    "p-2",
    "rounded",
    "mb-1",
    "max-w-[80%]",
    "break-words",
    isMine
      ? "bg-blue-600 text-white self-end ml-auto text-right"
      : "bg-gray-700 text-white text-left"
  );
  div.textContent = msg.message;
  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
});

// Send message function
export function sendMessage(roomId, senderId, senderType, receiverId, receiverType, message) {
  socket.emit("sendMessage", {
    roomId,
    sender: { id: senderId, type: senderType },
    receiver: { id: receiverId, type: receiverType },
    message,
  });
}



window.socket = socket;
window.sendMessage = sendMessage;

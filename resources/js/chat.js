// resources/js/chat.js
import { io } from "socket.io-client";

// -----------------------------------
// 🌐 SOCKET SETUP
// -----------------------------------
const SOCKET_URL = "http://127.0.0.1:4000";
const socket = io(SOCKET_URL, {
  transports: ["websocket"],
  reconnectionAttempts: 5,
  reconnectionDelay: 1000,
});

// ✅ After connecting, send userId to backend
socket.on("connect", () => {
  console.log("✅ [Socket Connected]", socket.id);
  const userIdEl = document.getElementById("chat-box");
  if (userIdEl) {
    const userId = userIdEl.dataset.userId;
    socket.emit("userConnected", userId); // 🟢 inform backend
  }
});
socket.on("disconnect", () => console.log("🔴 [Socket Disconnected]"));
socket.on("connect_error", (err) => console.error("⚠️ [Socket Error]", err.message));

// -----------------------------------
// 🧩 Utility
// -----------------------------------
function normalizeRoomId(roomId) {
  if (!roomId) return "";
  if (typeof roomId === "object") {
    return String(roomId._id ?? roomId.id ?? "");
  }
  return String(roomId);
}

// -----------------------------------
// 📦 Global State
// -----------------------------------
let onlineUsers = new Set();

// -----------------------------------
// 🟢 ONLINE / OFFLINE TRACKING
// -----------------------------------
socket.on("userOnline", (userId) => {
  onlineUsers.add(userId);
  updateOnlineStatus(true);
});

socket.on("userOffline", (userId) => {
  onlineUsers.delete(userId);
  updateOnlineStatus(false);
});

function updateOnlineStatus(isOnline) {
  const status = document.getElementById("user-status");
  if (!status) return;
  status.textContent = isOnline ? "Online" : "Offline";
  status.className = isOnline
    ? "text-xs text-green-400 font-medium"
    : "text-xs text-gray-400 font-medium";
}



// -----------------------------------
// ✅ MESSAGE SEEN UPDATE HANDLER
// -----------------------------------
socket.on("messageSeenUpdate", (msg) => {
  const chatBox = document.getElementById("chat-box");
  const seenTag = document.querySelector(`[data-msg-id="${msg._id}"] .seen-tag`);
  if (seenTag) seenTag.textContent = "✓ Seen";
});

// -----------------------------------
// 📨 SEND MESSAGE FUNCTION
// -----------------------------------
export function sendMessage(
  roomId,
  senderId,
  senderType,
  receiverId,
  receiverType,
  message,
  file = null
) {
  const payload = {
    roomId: normalizeRoomId(roomId),
    sender: { id: senderId, type: senderType },
    receiver: { id: receiverId, type: receiverType },
    message,
  };

  if (file) {
    const formData = new FormData();
    formData.append("roomId", payload.roomId);
    formData.append("sender", JSON.stringify(payload.sender));
    formData.append("receiver", JSON.stringify(payload.receiver));
    if (message) formData.append("message", message);
    formData.append("file", file);

    fetch("http://127.0.0.1:4000/api/send", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => console.log("📎 [File Message Sent]", data))
      .catch((err) => console.error("❌ [File Upload Error]", err));
  } else {
    socket.emit("sendMessage", payload);
  }
}

// -----------------------------------
// 🌎 Make globals
// -----------------------------------
window.socket = socket;
window.sendMessage = sendMessage;

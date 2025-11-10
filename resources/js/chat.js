// resources/js/chat.js
import { io } from "socket.io-client";

// SOCKET SETUP
const SOCKET_URL = "http://127.0.0.1:4000";
console.log("🌐 SOCKET connecting to:", SOCKET_URL);

const socket = io(SOCKET_URL, {
  transports: ["websocket"],
  reconnectionAttempts: 5,
  reconnectionDelay: 1000,
});

// GLOBAL
window.socket = socket;

// CONNECT
socket.on("connect", () => {
  console.log("✅ Socket Connected:", socket.id);
  const userIdEl = document.getElementById("chat-box");
  if (userIdEl) {
    const userId = userIdEl.dataset.userId;
    socket.emit("userConnected", userId);
  }
});

socket.on("disconnect", () => console.warn("⚠️ Socket Disconnected"));
socket.on("connect_error", (err) => console.error("❌ Socket Error:", err.message));

// UTILITY
function normalizeRoomId(roomId) {
  if (!roomId) return "";
  if (typeof roomId === "object") {
    return String(roomId._id ?? roomId.id ?? "");
  }
  return String(roomId);
}

// 🔹 Track all rendered message IDs
const renderedMessages = new Set();

// RENDER MESSAGE (STRICT DUPLICATE CHECK)
function renderMessage(msg, userId, userType, source = "unknown") {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  console.log("🧩 renderMessage called from [" + source + "]", msg);

  // ✅ Consistent msgId generate karo
  const msgId = String(msg._id || msg.id || `${msg.roomId}-${msg.sender?.id}-${msg.createdAt}`);

  // ✅ Strict duplicate check
  if (renderedMessages.has(msgId)) {
    console.warn("⏩ Skipping duplicate render:", msgId, "from", source);
    return;
  }

  renderedMessages.add(msgId);
  console.log("✅ Rendering NEW message [" + msgId + "] from [" + source + "]");

  const div = document.createElement("div");
  div.classList.add(
    "p-3",
    "rounded-2xl",
    "max-w-[70%]",
    "break-words",
    "shadow-md",
    "w-fit",
    "my-1",
    "flex",
    "flex-col"
  );
  div.dataset.msgId = msgId;

  const isMine = String(msg.sender.id) === String(userId) && msg.sender.type === userType;

  if (isMine) {
    div.classList.add(
      "bg-gradient-to-r",
      "from-blue-600",
      "to-indigo-600",
      "text-white",
      "ml-auto",
      "self-end"
    );
  } else {
    div.classList.add("bg-[#1E293B]", "text-gray-200", "mr-auto", "self-start");
  }

  // IMAGE MESSAGE
  if (msg.fileUrl && msg.fileType === "image") {
    const imgContainer = document.createElement("div");
    imgContainer.classList.add("my-2", "rounded-xl", "overflow-hidden");

    const fullUrl = `${SOCKET_URL}${msg.fileUrl}`;
    console.log("🖼️ Loading image:", fullUrl);

    const img = document.createElement("img");
    img.src = fullUrl;
    img.alt = "sent image";
    img.classList.add(
      "block",
      "rounded-xl",
      "max-w-[350px]",
      "max-h-[350px]",
      "object-contain",
      "cursor-pointer",
      "transition-transform",
      "duration-200",
      "hover:scale-[1.03]"
    );

    img.onload = () => console.log("✅ Image loaded successfully:", fullUrl);
    img.onerror = () => console.error("❌ Image failed to load:", fullUrl);
    img.onclick = () => window.open(fullUrl, "_blank");

    imgContainer.appendChild(img);
    div.appendChild(imgContainer);
  }

  // NON-IMAGE FILE
  else if (msg.fileUrl) {
    const link = document.createElement("a");
    link.href = `${SOCKET_URL}${msg.fileUrl}`;
    link.textContent = "Download Attachment";
    link.classList.add("underline", "text-sm", "block", "mt-1");
    link.target = "_blank";
    div.appendChild(link);
  }

  // TEXT MESSAGE
  if (msg.message) {
    const text = document.createElement("div");
    text.textContent = msg.message;
    text.classList.add("whitespace-pre-wrap", "leading-relaxed");
    div.appendChild(text);
  }

  // SEEN STATUS
  if (isMine) {
    const seen = document.createElement("span");
    seen.textContent = msg.seen ? "Seen" : "Sent";
    seen.classList.add("block", "text-xs", "mt-1", "text-gray-300", "seen-tag");
    div.appendChild(seen);
  }

  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
  console.log("📜 Chat box updated, scrolled to bottom.");
}

// SEND MESSAGE
export function sendMessage(roomId, senderId, senderType, receiverId, receiverType, message, file = null) {
  console.log("✉️ sendMessage called", { roomId, senderId, senderType, receiverId, receiverType, message, file });

  const payload = {
    roomId: normalizeRoomId(roomId),
    sender: { id: senderId, type: senderType },
    receiver: { id: receiverId, type: receiverType },
    message,
  };

  if (file) {
    console.log("📤 Uploading file:", file.name, file.type, file.size);
    const formData = new FormData();
    formData.append("roomId", payload.roomId);
    formData.append("sender", JSON.stringify(payload.sender));
    formData.append("receiver", JSON.stringify(payload.receiver));
    if (message) formData.append("message", message);
    formData.append("file", file);

    fetch(`${SOCKET_URL}/api/send`, {
      method: "POST",
      body: formData,
    })
      .then(res => {
        console.log("📥 Upload response status:", res.status);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => {
        console.log("✅ Upload response JSON:", data);
        if (data.success) {
          console.log("🚀 Emitting sendMessage after upload:", data.message);
          window.socket.emit("sendMessage", {
            ...payload,
            fileUrl: data.message.fileUrl,
            fileType: data.message.fileType,
          });
        }
      })
      .catch(err => console.error("❌ Upload Error:", err));
  } else {
    window.socket.emit("sendMessage", payload);
  }
}

// NEW MESSAGE EVENT
window.socket.on("newMessage", (msg) => {
  console.log("🆕 [SOCKET] New message event received:", msg);
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;
  const userId = chatBox.dataset.userId;
  const userType = chatBox.dataset.userType;

  renderMessage(msg, userId, userType, "socket");

  if (String(msg.receiver.id) === String(userId) && msg.receiver.type === userType) {
    window.socket.emit("messageSeen", {
      messageId: msg._id,
      roomId: msg.roomId,
      seenBy: { id: userId, type: userType },
    });
  }
});

// ONLINE / OFFLINE STATUS
window.socket.on("userOnline", () => {
  const status = document.getElementById("user-status");
  if (status) {
    status.textContent = "Online";
    status.className = "text-xs text-green-400 font-medium";
  }
});

window.socket.on("userOffline", () => {
  const status = document.getElementById("user-status");
  if (status) {
    status.textContent = "Offline";
    status.className = "text-xs text-gray-400 font-medium";
  }
});

// GLOBALS
window.sendMessage = sendMessage;
window.renderMessage = renderMessage;

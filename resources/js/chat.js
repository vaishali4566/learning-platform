// resources/js/chat.js
import { io } from "socket.io-client";

const SOCKET_URL = "http://127.0.0.1:4000";
console.log("🌐 Connecting to Socket Server:", SOCKET_URL);

// -------------------- SOCKET INIT --------------------
if (!window.socket) {
  window.socket = io(SOCKET_URL, {
    transports: ["websocket"],
    reconnectionAttempts: 5,
    reconnectionDelay: 1000,
  });
}
const socket = window.socket;

socket.on("connect", () => {
  console.log("✅ Socket Connected:", socket.id);
  const chatBox = document.getElementById("chat-box");
  const userId = chatBox?.dataset.userId || window.currentUser?.id;

  if (userId) {
    socket.emit("userConnected", userId);
    console.log("📡 Emitted userConnected for:", userId);
  }
});

socket.on("disconnect", () => console.warn("⚠️ Socket Disconnected"));
socket.on("connect_error", (err) => console.error("❌ Socket Error:", err.message));

// -------------------- HELPERS --------------------
function normalizeRoomId(roomId) {
  if (!roomId) return "";
  if (typeof roomId === "object") return String(roomId._id ?? roomId.id ?? "");
  return String(roomId);
}

const renderedMessages = new Set();

// -------------------- NOTIFICATION POPUP --------------------
let notifContainer = document.getElementById("notification-container");
if (!notifContainer) {
  notifContainer = document.createElement("div");
  notifContainer.id = "notification-container";
  Object.assign(notifContainer.style, {
    position: "fixed",
    top: "1rem",
    right: "1rem",
    zIndex: 9999,
    display: "flex",
    flexDirection: "column",
    gap: "0.5rem",
  });
  document.body.appendChild(notifContainer);
}


// -------------------- SHOW notification --------------------
function showNotification(title, body, roomId = null, senderId = null, senderType = null) {
  console.log("🔔 Showing Notification:", title, body);

  const notif = document.createElement("div");
  notif.className = `
    flex items-start gap-3 bg-white/90 backdrop-blur-md border border-gray-200 
    px-5 py-4 rounded-2xl shadow-xl transition-all duration-300 
    translate-x-full opacity-0 cursor-pointer hover:shadow-2xl hover:-translate-y-1
  `;

  notif.innerHTML = `
    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold shadow-sm">
      💬
    </div>
    <div class="flex-1">
      <div class="font-semibold text-gray-900 text-sm">${title}</div>
      <div class="text-gray-600 text-sm mt-1 leading-snug">${body}</div>
    </div>
  `;

  notifContainer.appendChild(notif);

  // ✅ On click → go to sender’s chat route
  notif.onclick = () => {
    console.log("🧭 Redirecting to chat room:", { roomId, senderId, senderType });

    if (senderId && senderType) {
      window.location.href = `/chat/room/${senderId}?type=${senderType}`;
    } else if (roomId) {
      window.location.href = `/chat/room/${roomId}`;
    } else {
      window.location.href = `/chat`;
    }
  };

  // Slide-in animation
  setTimeout(() => {
    notif.classList.remove("translate-x-full", "opacity-0");
    notif.classList.add("translate-x-0", "opacity-100");
  }, 50);

  // Auto remove after 6s
  setTimeout(() => {
    notif.classList.add("translate-x-full", "opacity-0");
    setTimeout(() => notif.remove(), 500);
  }, 6000);
}




// -------------------- RENDER MESSAGE --------------------
function renderMessage(msg, userId, userType) {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const msgId = String(msg._id || msg.id || `${msg.roomId}-${msg.sender?.id}-${msg.createdAt}`);
  if (renderedMessages.has(msgId)) return;
  renderedMessages.add(msgId);

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
  if (isMine)
    div.classList.add(
      "bg-gradient-to-r",
      "from-blue-600",
      "to-indigo-600",
      "text-white",
      "ml-auto",
      "self-end"
    );
  else div.classList.add("bg-[#1E293B]", "text-gray-200", "mr-auto", "self-start");

  if (msg.fileUrl && msg.fileType === "image") {
    const img = document.createElement("img");
    img.src = `${SOCKET_URL}${msg.fileUrl}`;
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
    img.onclick = () => window.open(img.src, "_blank");
    div.appendChild(img);
  }

  if (msg.message) {
    const text = document.createElement("div");
    text.textContent = msg.message;
    text.classList.add("whitespace-pre-wrap", "leading-relaxed");
    div.appendChild(text);
  }

  chatBox.appendChild(div);
}

// -------------------- SCROLL HELPERS --------------------
function scrollToBottom(force = false) {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const isNearBottom =
    chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 200;

  if (force || isNearBottom) {
    chatBox.scrollTop = chatBox.scrollHeight;
  }
}

// -------------------- SEND MESSAGE --------------------
export function sendMessage(
  roomId,
  senderId,
  senderType,
  receiverId,
  receiverType,
  message,
  file = null
) {
  const senderName = window.currentUser?.name || "Unknown";
  const receiverName = window.currentReceiver?.name || "Unknown";

  const payload = {
    roomId: normalizeRoomId(roomId),
    sender: { id: senderId, type: senderType, name: senderName },
    receiver: { id: receiverId, type: receiverType, name: receiverName },
    message,
  };

  if (file) {
    const formData = new FormData();
    formData.append("roomId", payload.roomId);
    formData.append("sender", JSON.stringify(payload.sender));
    formData.append("receiver", JSON.stringify(payload.receiver));
    if (message) formData.append("message", message);
    formData.append("file", file);

    fetch(`${SOCKET_URL}/api/send`, { method: "POST", body: formData })
      .then((res) => res.json())
      .then((data) => {
        console.log("📥 File upload response:", data);
        if (data.success) {
          socket.emit("sendMessage", {
            ...payload,
            fileUrl: data.message.fileUrl,
            fileType: data.message.fileType,
          });
          scrollToBottom(true); // ✅ after sending, stay at bottom
        }
      })
      .catch((err) => console.error("❌ Upload Error:", err));
  } else {
    socket.emit("sendMessage", payload);
    scrollToBottom(true); // ✅ scroll down after sending
  }
}

// -------------------- SOCKET EVENTS --------------------
socket.on("newMessage", (msg) => {
  const chatBox = document.getElementById("chat-box");
  const userId = chatBox?.dataset.userId || window.currentUser?.id;
  const userType = chatBox?.dataset.userType || window.currentUser?.type;
  const currentRoomId = chatBox?.dataset.roomId || window.currentRoomId || null;

  console.log("💬 newMessage received:", {
    msgId: msg._id,
    senderId: msg.sender?.id,
    receiverId: msg.receiver?.id,
    currentUserId: userId,
    roomId: msg.roomId,
    currentRoomId,
  });

  if (normalizeRoomId(msg.roomId) === normalizeRoomId(currentRoomId)) {
    renderMessage(msg, userId, userType);

    // ✅ Scroll only if user is near bottom
    scrollToBottom();

    if (String(msg.receiver.id) === String(userId) && msg.receiver.type === userType) {
      socket.emit("messageSeen", { messageId: msg._id, roomId: msg.roomId });
    }
  }
});

socket.on("receiveNotification", (data) => {
  console.log("🔔 Notification event received:", data);
  showNotification(data.title, data.body, data.roomId, data.from?.id, data.from?.type);
});

// -------------------- ONLINE/OFFLINE STATUS --------------------
socket.on("userStatusChange", ({ userId, status }) => {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const receiverId = chatBox.dataset.receiverId;
  if (String(receiverId) === String(userId)) {
    const statusElem = document.getElementById("user-status");
    if (statusElem) {
      if (status === "online") {
        statusElem.textContent = "Online";
        statusElem.className = "text-xs text-green-400 font-medium";
      } else {
        statusElem.textContent = "Offline";
        statusElem.className = "text-xs text-gray-400 font-medium";
      }
    }
  }
});

window.sendMessage = sendMessage;
window.renderMessage = renderMessage;
window.showNotification = showNotification;
window.scrollToBottom = scrollToBottom;

// ✅ When chat loads, always start at bottom
// ✅ Always open chat at the bottom — after messages render
window.addEventListener("load", () => {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  // Wait a short time for Laravel to render old messages
  setTimeout(() => {
    chatBox.scrollTop = chatBox.scrollHeight; // instantly go to bottom
    console.log("⬇️ Scrolled to bottom after load");
  }, 400); // adjust delay if messages are many (e.g., 600–800ms)
});

console.log("🟢 Chat.js initialized successfully");

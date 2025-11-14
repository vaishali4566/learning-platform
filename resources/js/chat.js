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
    const userType = chatBox?.dataset.userType || window.currentUser?.type || "user";
    socket.emit("userConnected", { id: userId, type: userType });
    console.log("📡 Emitted userConnected for:", { id: userId, type: userType });
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
    relative flex items-start gap-3 bg-white/90 backdrop-blur-md border border-gray-200 
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
    <button class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm font-bold">&times;</button>
  `;

  notifContainer.appendChild(notif);

  // Close button
  const closeBtn = notif.querySelector("button");
  closeBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    notif.remove();
  });

  // On click redirect to the chat room
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

  // Auto remove
  setTimeout(() => {
    notif.classList.add("translate-x-full", "opacity-0");
    setTimeout(() => notif.remove(), 500);
  }, 6000);
}

function getTickIcon(seen, delivered) {
  // seen → double blue, delivered → double gray, else single gray
  if (seen) return `<span class="tick seen" style="color:#f5f5f5;">&#10003;&#10003;</span>`; // double blue
  if (delivered) return `<span class="tick delivered" style="color:#929ea6;">&#10003;&#10003;</span>`; // double gray
  return `<span class="tick single" style="color:#929ea6;">&#10003;</span>`; // single gray
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
  if (isMine) {
    div.classList.add(
      "bg-gradient-to-r",
      "from-blue-600",
      "to-indigo-600",
      "text-white",
      "ml-auto",
      "self-end"
    );
    // mark element for tick updates
    div.classList.add("my-sent-msg");
  } else {
    div.classList.add("bg-[#1E293B]", "text-gray-200", "mr-auto", "self-start");
  }

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
    const textWrapper = document.createElement("div");
    textWrapper.classList.add("flex", "items-end", "gap-1");

    const text = document.createElement("div");
    text.textContent = msg.message;
    text.classList.add("whitespace-pre-wrap", "leading-relaxed");

    // Add tick container only for sender's own messages
    if (isMine) {
      const tick = document.createElement("span");
      tick.classList.add("text-xs", "ml-1", "opacity-80");
      tick.dataset.msgId = msgId;
      // use msg.seen and msg.delivered flags (fallback to false)
      tick.innerHTML = getTickIcon(Boolean(msg.seen), Boolean(msg.delivered));
      textWrapper.appendChild(text);
      textWrapper.appendChild(tick);
    } else {
      textWrapper.appendChild(text);
    }

    div.appendChild(textWrapper);
  }

  chatBox.appendChild(div);
}

// -------------------- SCROLL HELPERS --------------------
function scrollToBottom(force = false) {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const isNearBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 200;

  if (force || isNearBottom) {
    chatBox.scrollTop = chatBox.scrollHeight;
  }
}

// -------------------- SEND MESSAGE --------------------
export async function sendMessage(
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
    message: message || "",
  };

  // ---- FILE FLOW: upload via API, then broadcast only ----
  if (file) {
    try {
      const formData = new FormData();
      formData.append("file", file);
      formData.append("roomId", payload.roomId);
      formData.append("sender", JSON.stringify(payload.sender));
      formData.append("receiver", JSON.stringify(payload.receiver));
      if (message) formData.append("message", message);

      const res = await fetch(`${SOCKET_URL}/api/send`, {
        method: "POST",
        body: formData,
      });
      const data = await res.json();

      if (data?.success && data.message) {
        // server already saved to DB, now ask socket server to broadcast the saved message
        socket.emit("broadcastMessage", data.message);

        // render locally immediately (set delivered/seen flags if provided)
        const chatBox = document.getElementById("chat-box");
        const userId = chatBox?.dataset.userId || window.currentUser?.id;
        const userType = chatBox?.dataset.userType || window.currentUser?.type;
        renderMessage(data.message, userId, userType);
        scrollToBottom(true);
      } else {
        console.error("Upload failed or invalid response", data);
      }
    } catch (err) {
      console.error("❌ Upload Error:", err);
    }

    return; // stop here
  }

  // ---- TEXT FLOW: use existing socket sendMessage path (server saves & broadcasts) ----
  socket.emit("sendMessage", payload);

  // Render locally (optimistic) — ensure delivered=false, seen=false initially
  const optimistic = {
    _id: `tmp-${Date.now()}`,
    roomId: payload.roomId,
    sender: payload.sender,
    receiver: payload.receiver,
    message: payload.message,
    seen: false,
    delivered: false,
    createdAt: new Date().toISOString(),
  };
  const chatBox = document.getElementById("chat-box");
  const userId = chatBox?.dataset.userId || window.currentUser?.id;
  const userType = chatBox?.dataset.userType || window.currentUser?.type;
  renderMessage(optimistic, userId, userType);

  scrollToBottom(true); // scroll after sending
}

// -------------------- SOCKET EVENTS --------------------
// -------------------- NEW MESSAGE (patched to avoid duplicates) --------------------
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

  // Only handle message for the active room
  if (normalizeRoomId(msg.roomId) !== normalizeRoomId(currentRoomId)) {
    // Outside current room → show notification only
    return;
  }

  const isMine = String(msg.sender.id) === String(userId) && msg.sender.type === userType;

  // ✅ If it's my message (I sent it), replace the optimistic temp message
  if (isMine) {
    const optimisticDiv = chatBox.querySelector(`[data-msg-id^="tmp-"]`);
    if (optimisticDiv) {
      const tick = optimisticDiv.querySelector("span[data-msg-id]");
      if (tick) {
        tick.dataset.msgId = msg._id;
        tick.innerHTML = getTickIcon(Boolean(msg.seen), Boolean(msg.delivered));
      }

      // Update the DOM element attributes
      optimisticDiv.dataset.msgId = msg._id;
      renderedMessages.add(String(msg._id));
      renderedMessages.delete(optimisticDiv.dataset.msgId);

      console.log("🔄 Replaced optimistic message with real DB message:", msg._id);
      return; // stop here → don't re-render duplicate
    }
  }

  // ✅ Otherwise (incoming message from other user), render normally
  renderMessage(msg, userId, userType);
  scrollToBottom();

  // ✅ If current user is receiver, mark as seen
  if (String(msg.receiver.id) === String(userId) && msg.receiver.type === userType) {
    socket.emit("messageSeen", {
      messageId: msg._id,
      roomId: msg.roomId,
      user: { id: userId, type: userType },
    });
  }
});


// When server informs message was seen
socket.on("messageSeenUpdate", (updatedMsg) => {
  const id = updatedMsg._id || updatedMsg;
  const tick = document.querySelector(`span[data-msg-id="${id}"]`);
  if (tick) {
    // Make it double-blue
    tick.innerHTML = getTickIcon(true, true);
    // optional: add animation class
    tick.classList.add("animate-fade");
  }
});

// When server informs message delivered
socket.on("messageDelivered", ({ messageId }) => {
  const tick = document.querySelector(`span[data-msg-id="${messageId}"]`);
  if (tick) {
    tick.innerHTML = getTickIcon(false, true); // delivered gray
  }
});

socket.on("receiveNotification", (data) => {
  const senderName = data.from?.name || "Someone"; // fallback
  const title = `${senderName} sent you a message`;
  showNotification(title, data.body, data.roomId, data.from?.id, data.from?.type);
});


socket.on("unfriendUpdate", ({ userId, userType }) => {
  console.log("❌ Unfriend Update:", userId, userType);

  // Example: show popup notification
  showNotification("Unfriended", "You are no longer friends");

  // If user is in same chat, disable input
  if (window.currentReceiver?.id == userId && window.currentReceiver?.type == userType) {
    const input = document.getElementById("message-input");
    if (input) {
      input.disabled = true;
      input.placeholder = "You are no longer friends.";
    }
  }
});


// -------------------- ONLINE/OFFLINE STATUS --------------------
socket.on("userStatusChange", ({ id, type, status }) => {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  const receiverId = chatBox.dataset.receiverId;
  const receiverType = chatBox.dataset.receiverType;

  // Compare using both type + id
  if (String(receiverId) === String(id) && receiverType === type) {
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

    // When receiver becomes online, update delivered ticks for messages that belong to this chat
    if (status === "online") {
      const chatBox = document.getElementById("chat-box");
      if (chatBox) {
        const myUserId = chatBox.dataset.userId || window.currentUser?.id;
        const myUserType = chatBox.dataset.userType || window.currentUser?.type;

        // Find all my sent messages in DOM and set delivered if not already seen
        chatBox.querySelectorAll("[data-msg-id]").forEach((el) => {
          const tick = el.querySelector("span[data-msg-id]");
          if (tick && el.classList.contains("my-sent-msg")) {
            // If single tick (not double) then toggle to delivered double gray
            if (tick.innerHTML.includes("&#10003;") && !tick.innerHTML.includes("&#10003;&#10003;")) {
              tick.innerHTML = getTickIcon(false, true);
            }
          }
        });
      }
    }
  }
});

// -------------------- EXPORTS & GLOBALS --------------------
window.sendMessage = sendMessage;
window.renderMessage = renderMessage;
window.showNotification = showNotification;
window.scrollToBottom = scrollToBottom;

// -------------------- On page load: join room & sync --------------------
window.addEventListener("load", () => {
  const chatBox = document.getElementById("chat-box");
  if (!chatBox) return;

  // Wait a short time for server-rendered messages to appear
  setTimeout(() => {
    chatBox.scrollTop = chatBox.scrollHeight; // go to bottom
    console.log("⬇️ Scrolled to bottom after load");
  }, 400);

  // --- Join the room and trigger server-side seen sync ---
  const roomId = chatBox.dataset.roomId || window.currentRoomId;
  const userId = chatBox.dataset.userId || window.currentUser?.id;
  const userType = chatBox.dataset.userType || window.currentUser?.type;

    if (roomId && userId && userType) {
    socket.emit("join", { roomId, user: { id: userId, type: userType } });
    console.log("🪄 Joined room and syncing seen status:", { roomId, userId, userType });

    // ✅ Check receiver’s online status on reload
    const receiverId = chatBox.dataset.receiverId || window.currentReceiver?.id;
    const receiverType = chatBox.dataset.receiverType || window.currentReceiver?.type;
    if (receiverId && receiverType) {
      console.log("🧭 Checking receiver online status after reload:", { receiverId, receiverType });
      socket.emit("checkUserStatus", { id: receiverId, type: receiverType });
    }
  }


  // --- Fetch room messages once to sync tick states after reload ---
  if (roomId) {
    fetch(`${SOCKET_URL}/api/messages/${roomId}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success && Array.isArray(data.messages)) {
          data.messages.forEach((msg) => {
            // If DOM contains a tick for this msg, update it using server flags
            const tick = document.querySelector(`span[data-msg-id="${msg._id}"]`);
            if (tick) {
              tick.innerHTML = getTickIcon(Boolean(msg.seen), Boolean(msg.delivered));
            }
          });
          console.log("🔄 Synced message ticks after reload");
        }
      })
      .catch((err) => console.error("❌ Sync fetch error:", err));
  }
});

console.log("🟢 Chat.js initialized successfully");

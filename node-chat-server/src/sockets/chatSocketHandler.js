import Message from "../models/Message.js";

const onlineUsers = new Map(); // userId -> socketId

export function registerSocketHandlers(io) {
  io.on("connection", (socket) => {
    console.log("✅ Connected:", socket.id);

    // ---------------- USER CONNECTED ----------------
    socket.on("userConnected", (userId) => {
      if (!userId) return;
      onlineUsers.set(String(userId), socket.id);
      console.log("🟢 Online users:", Array.from(onlineUsers.keys()));
      io.emit("userStatusChange", { userId, status: "online" });
    });

    // ---------------- JOIN ROOM ----------------
    socket.on("join", ({ roomId }) => {
      if (!roomId) return;
      socket.join(roomId);
      console.log(`👥 Joined room: ${roomId}`);
    });

    // ---------------- SEND MESSAGE ----------------
    socket.on("sendMessage", async (data) => {
      try {
        const { roomId, sender, receiver, message, fileUrl, fileType } = data;

        if (fileUrl || fileType) return; // handled by API

        const newMsg = await Message.create({
          roomId,
          sender,
          receiver,
          message: message || null,
          fileUrl: null,
          fileType: null,
          seen: false,
        });

        io.in(roomId).emit("newMessage", newMsg);

        const receiverSocketId = onlineUsers.get(String(receiver.id));
        const receiverSocket =
          receiverSocketId && io.sockets.sockets.get(receiverSocketId);

        if (receiverSocket && !receiverSocket.rooms.has(roomId)) {
          io.to(receiverSocketId).emit("receiveNotification", {
            title: `${sender?.name || "Someone"} sent you a message`,
            body: message || "📎 Sent a file",
            from: {
              id: sender?.id,
              type: sender?.type,
              name: sender?.name || "Unknown",
            },
            roomId,
          });
        }

        console.log(`💬 Message sent to room ${roomId} by ${sender?.name}`);
      } catch (err) {
        console.error("❌ sendMessage error:", err);
      }
    });

    // ---------------- MESSAGE SEEN ----------------
    socket.on("messageSeen", async ({ messageId, roomId }) => {
      try {
        const updated = await Message.findByIdAndUpdate(
          messageId,
          { seen: true },
          { new: true }
        );
        if (updated) io.in(roomId).emit("messageSeenUpdate", updated);
      } catch (err) {
        console.error("❌ Seen update error:", err);
      }
    });

    // ---------------- DISCONNECT ----------------
    socket.on("disconnect", () => {
      console.log("⚠️ Disconnected:", socket.id);

      let disconnectedUserId = null;
      for (let [userId, socketId] of onlineUsers.entries()) {
        if (socketId === socket.id) {
          disconnectedUserId = userId;
          onlineUsers.delete(userId);
          break;
        }
      }

      if (disconnectedUserId) {
        console.log(`🔴 User ${disconnectedUserId} went offline`);
        io.emit("userStatusChange", {
          userId: disconnectedUserId,
          status: "offline",
        });
      }
    });
  });
}

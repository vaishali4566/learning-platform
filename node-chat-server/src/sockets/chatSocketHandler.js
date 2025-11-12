// src/sockets/chatSocketHandler.js
import Message from "../models/Message.js";

export const chatSocketHandler = (io, socket, onlineUsers) => {
  console.log("✅ Connected:", socket.id);

  socket.on("userConnected", (userId) => {
    if (!userId) return;
    onlineUsers.set(userId, socket.id);
    io.emit("userStatusChange", { userId, status: "online" });
  });

  socket.on("join", ({ roomId }) => socket.join(roomId));

  socket.on("sendMessage", async (data) => {
    try {
      const { roomId, sender, receiver, message, fileUrl, fileType } = data;
      const newMsg = await Message.create({
        roomId,
        sender,
        receiver,
        message: message || null,
        fileUrl: fileUrl || null,
        fileType: fileType || null,
        seen: false,
      });

      io.in(roomId).emit("newMessage", newMsg);

      // Optional: send notification if receiver is online but not in room
      const receiverSocketId = onlineUsers.get(String(receiver.id));
      const receiverSocket = io.sockets.sockets.get(receiverSocketId);

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
    } catch (err) {
      console.error("❌ Message error:", err);
    }
  });

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

  socket.on("disconnect", () => {
    let disconnectedUserId = null;
    for (let [userId, socketId] of onlineUsers.entries()) {
      if (socketId === socket.id) {
        disconnectedUserId = userId;
        onlineUsers.delete(userId);
        break;
      }
    }

    if (disconnectedUserId) {
      io.emit("userStatusChange", { userId: disconnectedUserId, status: "offline" });
    }

    console.log(`⚠️ Disconnected: ${socket.id}`);
  });
};

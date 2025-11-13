import Message from "../models/Message.js";

const onlineUsers = new Map(); // key = `${type}_${id}` → socketId

export function registerSocketHandlers(io) {
  io.on("connection", (socket) => {
    console.log("✅ Connected:", socket.id);

    // ---------------- USER CONNECTED ----------------
    socket.on("userConnected", ({ id, type }) => {
      if (!id || !type) return;

      const key = `${type}_${id}`;
      onlineUsers.set(key, socket.id);

      console.log("🟢 Online users:", Array.from(onlineUsers.keys()));

      io.emit("userStatusChange", { id, type, status: "online" });
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

        // If file exists → ignore (handled via API route)
        if (fileUrl || fileType) return;

        // Save message to DB
        const newMsg = await Message.create({
          roomId,
          sender,
          receiver,
          message: message || null,
          fileUrl: null,
          fileType: null,
          seen: false,
        });

        // Emit to users in that room
        io.in(roomId).emit("newMessage", newMsg);

        // Notify receiver if online and not in same room
        const receiverKey = `${receiver.type}_${receiver.id}`;
        const receiverSocketId = onlineUsers.get(receiverKey);
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

    // ---------------- CHECK USER STATUS (ADD HERE) ----------------
    socket.on("checkUserStatus", ({ id, type }) => {
      const key = `${type}_${id}`;
      const isOnline = onlineUsers.has(key);
      socket.emit("userStatusChange", { id, type, status: isOnline ? "online" : "offline" });
    });

    // ---------------- DISCONNECT ----------------
    socket.on("disconnect", () => {
      console.log("⚠️ Disconnected:", socket.id);

      let disconnectedKey = null;
      for (let [key, socketId] of onlineUsers.entries()) {
        if (socketId === socket.id) {
          disconnectedKey = key;
          onlineUsers.delete(key);
          break;
        }
      }

      if (disconnectedKey) {
        const [type, id] = disconnectedKey.split("_");
        console.log(`🔴 ${type} ${id} went offline`);
        io.emit("userStatusChange", { id, type, status: "offline" });
      }
    });
  });
}

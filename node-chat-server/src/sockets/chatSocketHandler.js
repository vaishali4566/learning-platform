// sockets/chatSockethandlers.js
import Message from "../models/Message.js";

export const onlineUsers = new Map(); // key = `${type}_${id}` → socketId

export function registerSocketHandlers(io) {
  io.on("connection", (socket) => {
    console.log("✅ Connected:", socket.id);


    // ---------------- USER CONNECTED ----------------
    socket.on("userConnected", async ({ id, type }) => {
      if (!id || !type) return;

      const key = `${type}_${id}`;
      onlineUsers.set(key, socket.id);

      console.log("🟢 Online users:", Array.from(onlineUsers.keys()));

      // Broadcast status change
      io.emit("userStatusChange", { id, type, status: "online" });

      // ✅ NEW LOGIC: Mark undelivered messages as delivered (double gray)
      try {
        const undelivered = await Message.find({
          "receiver.id": String(id),
          "receiver.type": String(type),
          delivered: false,
        });

        if (undelivered.length > 0) {
          const ids = undelivered.map((m) => m._id);
          await Message.updateMany(
            { _id: { $in: ids } },
            { $set: { delivered: true } }
          );

          // Notify each sender so they see double gray ticks
          for (const msg of undelivered) {
            const senderKey = `${msg.sender.type}_${msg.sender.id}`;
            const senderSocketId = onlineUsers.get(senderKey);
            if (senderSocketId) {
              io.to(senderSocketId).emit("messageDelivered", { messageId: msg._id });
            }
          }

          console.log(`📬 Marked ${undelivered.length} messages as delivered for ${type}_${id}`);
        }
      } catch (err) {
        console.error("❌ Delivery sync error on userConnected:", err);
      }
    });


    // ---------------- JOIN ROOM ----------------
    // Frontend should emit join({ roomId, user: { id, type }})
    socket.on("join", async ({ roomId, user }) => {
      if (!roomId || !user || !user.id || !user.type) return;
      socket.join(roomId);
      console.log(`👥 ${user.type}_${user.id} joined room: ${roomId}`);

      try {
        // Find unseen messages where current user is receiver
        const unseenMessages = await Message.find({
          roomId,
          "receiver.id": String(user.id),
          "receiver.type": String(user.type),
          seen: false,
        });

        if (unseenMessages.length > 0) {
          // Mark them as seen
          const ids = unseenMessages.map((m) => m._id);
          await Message.updateMany(
            { _id: { $in: ids } },
            { $set: { seen: true, delivered: true } } // delivered true because receiver opened the chat
          );

          // Notify original senders directly (so they get blue tick even if not connected to room)
          for (const msg of unseenMessages) {
            const senderKey = `${msg.sender.type}_${msg.sender.id}`;
            const senderSocketId = onlineUsers.get(senderKey);

            // Send update to the sender socket directly
            if (senderSocketId) {
              io.to(senderSocketId).emit("messageSeenUpdate", {
                _id: msg._id,
                roomId,
                seen: true,
                delivered: true,
              });
            }

            // Also broadcast to the room (so receiver's own tab and any other connected clients in room see it)
            io.in(roomId).emit("messageSeenUpdate", {
              _id: msg._id,
              roomId,
              seen: true,
              delivered: true,
            });
          }

          console.log(`👁️ Marked ${unseenMessages.length} messages as seen in room ${roomId}`);
        }
      } catch (err) {
        console.error("❌ join-room seen sync error:", err);
      }
    });

    // ---------------- SEND MESSAGE ----------------
    socket.on("sendMessage", async (data) => {
      try {
        const { roomId, sender, receiver, message, fileUrl, fileType } = data;

        // If file path provided via socket (not used here) — ignore (handled by upload route)
        if (fileUrl || fileType) return;

        // Detect if receiver is online and whether they are already in the same room
        const receiverKey = `${receiver.type}_${receiver.id}`;
        const receiverSocketId = onlineUsers.get(receiverKey);
        const receiverSocket = receiverSocketId && io.sockets.sockets.get(receiverSocketId);

        // delivered: receiver online but not in same room -> we consider message 'delivered'
        const delivered = !!(receiverSocket && !receiverSocket.rooms.has(roomId));

        // Save message to DB (include delivered flag)
        const newMsg = await Message.create({
          roomId,
          sender,
          receiver,
          message: message || null,
          fileUrl: null,
          fileType: null,
          seen: false,
          delivered: delivered,
        });

        // Emit to users in that room
        io.in(roomId).emit("newMessage", newMsg);

        // Notify receiver if online and not in same room
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

        // Inform sender socket that the message was delivered (if delivered = true)
        const senderKey = `${sender.type}_${sender.id}`;
        const senderSocketId = onlineUsers.get(senderKey);
        if (senderSocketId && delivered) {
          io.to(senderSocketId).emit("messageDelivered", { messageId: newMsg._id });
        }

        console.log(`💬 Message sent to room ${roomId} by ${sender?.name}`);
      } catch (err) {
        console.error("❌ sendMessage error:", err);
      }
    });

    // ---------------- COURSE FEEDBACK: JOIN ROOM ----------------
    socket.on("joinCourse", (courseId) => {
      if (!courseId) return;

      socket.join(`course_${courseId}`);
      console.log(`📚 User joined course feedback room: course_${courseId}`);
    });

    // ---------------- MESSAGE SEEN ----------------
    // This event may be emitted when receiver sees a message while in the room (or by join handler above).
    socket.on("messageSeen", async ({ messageId, roomId, user }) => {
      try {
        const updated = await Message.findByIdAndUpdate(
          messageId,
          { seen: true, delivered: true },
          { new: true }
        );

        if (updated) {
          // Notify entire room and also directly notify sender(s) so they receive update
          io.in(roomId).emit("messageSeenUpdate", updated);

          const senderKey = `${updated.sender.type}_${updated.sender.id}`;
          const senderSocketId = onlineUsers.get(senderKey);
          if (senderSocketId) {
            io.to(senderSocketId).emit("messageSeenUpdate", {
              _id: updated._id,
              roomId,
              seen: true,
              delivered: true,
            });
          }
        }
      } catch (err) {
        console.error("❌ Seen update error:", err);
      }
    });

    // ---------------- CHECK USER STATUS ----------------
    // client can ask for a user's status; server responds directly to the caller
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

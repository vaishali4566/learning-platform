// src/sockets/chatSocketHandler.js
import Message from "../models/Message.js";

export const onlineUsers = new Map(); // key = "type_id" → socketId

export function registerChatSocketHandlers(io, socket) {
  console.log("⚡ Chat socket active:", socket.id);

  // ---------------- USER CONNECTED ----------------
  socket.on("userConnected", ({ id, type }) => {
    if (!id || !type) return;

    const key = `${type}_${id}`;
    onlineUsers.set(key, socket.id);

    console.log("🟢 Online:", Array.from(onlineUsers.keys()));
    io.emit("userStatusChange", { id, type, status: "online" });
  });

  // ---------------- JOIN CHAT ROOM ----------------
  socket.on("join", async ({ roomId, user }) => {
    socket.join(roomId);
    console.log(`👥 Joined room ${roomId}`);

    const unseen = await Message.find({
      roomId,
      "receiver.id": user.id,
      "receiver.type": user.type,
      seen: false,
    });

    if (unseen.length > 0) {
      const ids = unseen.map((m) => m._id);
      await Message.updateMany(
        { _id: { $in: ids } },
        { $set: { seen: true, delivered: true } }
      );

      unseen.forEach((msg) => {
        const senderKey = `${msg.sender.type}_${msg.sender.id}`;
        const senderSK = onlineUsers.get(senderKey);
        if (senderSK) {
          io.to(senderSK).emit("messageSeenUpdate", msg);
        }
      });

      io.in(roomId).emit("messageSeenUpdateBulk", ids);
    }
  });

  // ---------------- SEND MESSAGE ----------------
  socket.on("sendMessage", async (data) => {
    const { roomId, sender, receiver, message } = data;

    const receiverKey = `${receiver.type}_${receiver.id}`;
    const receiverSocketId = onlineUsers.get(receiverKey);
    const receiverSocket =
      receiverSocketId && io.sockets.sockets.get(receiverSocketId);

    const delivered = !!(receiverSocket && !receiverSocket.rooms.has(roomId));

    const newMsg = await Message.create({
      roomId,
      sender,
      receiver,
      message,
      fileUrl: null,
      fileType: null,
      seen: false,
      delivered,
    });

    io.in(roomId).emit("newMessage", newMsg);

    if (receiverSocket && !receiverSocket.rooms.has(roomId)) {
      io.to(receiverSocketId).emit("receiveNotification", {
        from: sender,
        roomId,
        body: message,
      });
    }

    if (delivered) {
      const senderKey = `${sender.type}_${sender.id}`;
      const senderSK = onlineUsers.get(senderKey);
      if (senderSK) {
        io.to(senderSK).emit("messageDelivered", { messageId: newMsg._id });
      }
    }
  });

  // ---------------- SEEN ----------------
  socket.on("messageSeen", async ({ messageId, roomId }) => {
    const updated = await Message.findByIdAndUpdate(
      messageId,
      { seen: true, delivered: true },
      { new: true }
    );

    if (updated) {
      io.in(roomId).emit("messageSeenUpdate", updated);

      const senderKey = `${updated.sender.type}_${updated.sender.id}`;
      const senderSK = onlineUsers.get(senderKey);
      if (senderSK) io.to(senderSK).emit("messageSeenUpdate", updated);
    }
  });

  // ---------------- DISCONNECT ----------------
  socket.on("disconnect", () => {
    let removedKey = null;

    for (let [key, sId] of onlineUsers.entries()) {
      if (sId === socket.id) {
        removedKey = key;
        onlineUsers.delete(key);
        break;
      }
    }

    if (removedKey) {
      const [type, id] = removedKey.split("_");
      io.emit("userStatusChange", { id, type, status: "offline" });
    }
  });
}

// src/index.js
import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import { connectDB } from "./config/db.js";
import chatRoutes from "./routes/chatRoutes.js";
import Message from "./models/Message.js";
import path from "path";
import { fileURLToPath } from "url";

dotenv.config();
connectDB();

const app = express();

// RESOLVE UPLOADS PATH FROM ROOT (src/ -> ../uploads)
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const uploadsPath = path.join(__dirname, "uploads");

console.log("Serving uploads from:", uploadsPath);
app.use("/uploads", express.static(uploadsPath));

// Middleware
app.use(cors());
app.use(express.json());

// API routes
app.use("/api", chatRoutes);

const server = http.createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

// --------------------- ONLINE USER TRACKING ---------------------
const onlineUsers = new Map(); // userId -> socket.id

io.on("connection", (socket) => {
  console.log("✅ Connected:", socket.id);

  // Track online users
  socket.on("userConnected", (userId) => {
    if (!userId) return;
    onlineUsers.set(userId, socket.id);
    console.log("Online users:", Array.from(onlineUsers.keys()));

    // Notify all clients that this user is online
    io.emit("userStatusChange", { userId, status: "online" });
  });

  // Join room
  socket.on("join", ({ roomId }) => {
    socket.join(roomId);
  });

  // Send message
  socket.on("sendMessage", async (data) => {
  console.log("📨 Incoming message data:", data);

  try {
    const { roomId, sender, receiver, message, fileUrl, fileType } = data;

    // 🧩 Save the message in DB
    const newMsg = await Message.create({
      roomId,
      sender,
      receiver,
      message: message || null,
      fileUrl: fileUrl || null,
      fileType: fileType || null,
      seen: false,
    });

    // 📨 Emit to all users currently in that room
    io.in(roomId).emit("newMessage", newMsg);

    // ⚡ Send popup notification to receiver (if online & not in same room)
    const receiverSocketId = onlineUsers.get(String(receiver.id));
    const receiverSocket = io.sockets.sockets.get(receiverSocketId);

    if (receiverSocket && !receiverSocket.rooms.has(roomId)) {
      // 🔔 Send dynamic notification with sender info
      io.to(receiverSocketId).emit("receiveNotification", {
        title: `${sender?.name || "Someone"} sent you a message`,
        body: message || "📎 Sent a file",
        from: {
          id: sender?.id,
          type: sender?.type,
          name: sender?.name || "Unknown",
        },
        roomId, // for reference if needed later
      });

      console.log(`🔔 Sent notification to user ${receiver.id} from ${sender?.name}`);
    }
  } catch (err) {
    console.error("❌ Message save or emit error:", err);
  }
});




  // Message seen
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

  // Disconnect
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
      io.emit("userStatusChange", { userId: disconnectedUserId, status: "offline" });
    }
  });
});

const PORT = 4000;
server.listen(PORT, () => console.log(`🚀 Server running on ${PORT}`));

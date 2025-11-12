import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";
import { connectDB } from "./config/db.js";
import chatRoutes from "./routes/chatRoutes.js";
import { chatSocketHandler } from "./sockets/chatSocketHandler.js";
import { videoSocketHandler } from "./sockets/videoSocketHandler.js";

dotenv.config();
connectDB();

const app = express();
app.use(cors());
app.use(express.json());

// ---------------- PATH SETUP ----------------
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const uploadsPath = path.join(__dirname, "uploads");
app.use("/uploads", express.static(uploadsPath));

app.use(cors());
app.use(express.json());

// ---------------- ROUTES ----------------
app.use("/api", chatRoutes);

const server = http.createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

// ---------------- USER STATUS TRACKING ----------------
const onlineUsers = new Map(); // userId -> socketId

// centralize socket handling
io.on("connection", (socket) => {
  console.log("✅ Connected:", socket.id);

  // Track online users
  socket.on("userConnected", (userId) => {
    if (!userId) return;
    onlineUsers.set(String(userId), socket.id);
    console.log("🟢 Online users:", Array.from(onlineUsers.keys()));

    // Broadcast online status
    io.emit("userStatusChange", { userId, status: "online" });
  });

  // Join room
  socket.on("join", ({ roomId }) => {
    if (!roomId) return;
    socket.join(roomId);
    console.log(`👥 Joined room: ${roomId}`);
  });

  // ---------------- SEND MESSAGE (TEXT ONLY) ----------------
  socket.on("sendMessage", async (data) => {
    try {
      const { roomId, sender, receiver, message, fileUrl, fileType } = data;

      // 🚫 If file exists → ignore (handled via /api/send)
      if (fileUrl || fileType) {
        console.log("⚠️ Skipping file message — handled by API route.");
        return;
      }

      // 🧩 Save text message to DB
      const newMsg = await Message.create({
        roomId,
        sender,
        receiver,
        message: message || null,
        fileUrl: null,
        fileType: null,
        seen: false,
      });

      // 🔁 Emit to users in that room
      io.in(roomId).emit("newMessage", newMsg);

      // 🔔 Send popup to receiver if online but not in same room
      const receiverSocketId = onlineUsers.get(String(receiver.id));
      const receiverSocket = receiverSocketId ? io.sockets.sockets.get(receiverSocketId) : null;

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

const PORT = 4000;
server.listen(PORT, () =>
  console.log(`🚀 Server running on http://127.0.0.1:${PORT}`)
);

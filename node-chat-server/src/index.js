import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import { connectDB } from "./config/db.js";
import ChatRoom from "./models/ChatRoom.js";
import chatRoutes from "./routes/chatRoutes.js";
import Message from "./models/Message.js";
import path from "path";
import { fileURLToPath } from "url";

dotenv.config();
connectDB();

const app = express();
app.use(cors());
app.use(express.json());
app.use("/api", chatRoutes);

// 🗂️ Serve uploaded files
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
app.use("/uploads", express.static(path.join(__dirname, "uploads")));

const server = http.createServer(app);
const io = new Server(server, {
  cors: { origin: "*" },
});

// 🟢 Track online users
const onlineUsers = new Map();

io.on("connection", (socket) => {
  console.log("🟢 [Socket Connected] ID:", socket.id);

  // 👤 When user connects, frontend should emit "userConnected" with userId
  socket.on("userConnected", (userId) => {
    onlineUsers.set(userId, socket.id);
    console.log(`✅ [User Online] userId: ${userId}, socketId: ${socket.id}`);
    io.emit("userOnline", userId); // notify all clients
  });

  // 🧩 Join room
  socket.on("join", ({ roomId }) => {
    console.log(`📥 [Join Request] ${socket.id} joining room: ${roomId}`);
    socket.join(roomId);
    console.log(`✅ [Room Joined] ${socket.id} joined ${roomId}`);
  });

  socket.on("sendMessage", async (data) => {
  console.log("💬 [Incoming Message Event]", JSON.stringify(data, null, 2));

  try {
    const { roomId, sender, receiver, message, fileUrl, fileType } = data;

    if (!roomId || (!message && !fileUrl)) {
      console.log("⚠️ [Validation Error] Missing message or file or roomId:", roomId);
      socket.emit("error", { message: "Missing message or file" });
      return;
    }

    console.log("🧾 Sender:", sender, "Receiver:", receiver);

    // 💾 Save to DB
    const newMsg = await Message.create({
      roomId,
      sender,
      receiver,
      message,
      fileUrl: fileUrl || null,
      fileType: fileType || null,
      seen: false,
    });

    console.log("✅ [DB Save Success]", newMsg._id);

    // 📡 Emit message to room
    const roomClients = io.sockets.adapter.rooms.get(roomId);
    console.log("👥 [Room Clients]", roomId, roomClients ? [...roomClients] : "❌ None");

    io.in(roomId).emit("newMessage", newMsg);
  } catch (err) {
    console.error("❌ [Error saving message]:", err);
  }
});


  // 👁️‍🗨️ Mark message as seen
  socket.on("messageSeen", async ({ messageId, roomId, seenBy }) => {
    try {
      console.log(`👁️ [Seen Event] messageId: ${messageId} by ${seenBy?.id}`);

      const updatedMsg = await Message.findByIdAndUpdate(
        messageId,
        { seen: true },
        { new: true }
      );

      if (updatedMsg) {
        io.in(roomId).emit("messageSeenUpdate", updatedMsg);
      }
    } catch (err) {
      console.error("❌ [Error Updating Seen Status]", err);
    }
  });

  // 🔴 Disconnect
  socket.on("disconnect", () => {
    // Find which user disconnected
    const userId = [...onlineUsers.entries()].find(
      ([, sId]) => sId === socket.id
    )?.[0];

    if (userId) {
      onlineUsers.delete(userId);
      console.log(`🔴 [User Offline] userId: ${userId}`);
      io.emit("userOffline", userId); // notify all clients
    }

    console.log(`🔴 [Socket Disconnected] ${socket.id}`);
  });
});

const PORT = process.env.PORT || 4000;
server.listen(PORT, () =>
  console.log(`🚀 [Server Started] Socket.IO running on port ${PORT}`)
);

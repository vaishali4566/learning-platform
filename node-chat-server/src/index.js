// src/server.js
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

io.on("connection", (socket) => {
  console.log("Connected:", socket.id);

  socket.on("userConnected", (userId) => {
    // online tracking
  });

  socket.on("join", ({ roomId }) => {
    socket.join(roomId);
  });

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
    } catch (err) {
      console.error("Save error:", err);
    }
  });

  socket.on("messageSeen", async ({ messageId, roomId }) => {
    const updated = await Message.findByIdAndUpdate(messageId, { seen: true }, { new: true });
    if (updated) io.in(roomId).emit("messageSeenUpdate", updated);
  });

  socket.on("disconnect", () => {
    console.log("Disconnected:", socket.id);
  });
});

const PORT = 4000;
server.listen(PORT, () => console.log(`Server running on ${PORT}`));
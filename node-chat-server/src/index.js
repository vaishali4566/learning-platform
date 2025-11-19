// src/index.js
import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";

import { connectDB } from "./config/db.js";
import chatRoutes from "./routes/chatRoutes.js";
import { registerChatSocketHandlers } from "./sockets/chatSocketHandler.js";
import { videoSocketHandler } from "./sockets/videoSocketHandler.js";

dotenv.config();
connectDB();

const app = express();
app.use(cors());
app.use(express.json());

// Static uploads folder
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
app.use("/uploads", express.static(path.join(__dirname, "uploads")));

// Routes
app.use("/api", chatRoutes);

// ---------------- SERVER + SOCKET ----------------
const server = http.createServer(app);
export const io = new Server(server, { cors: { origin: "*" } });

// Main socket handler entry
io.on("connection", (socket) => {
  console.log("🔌 User connected:", socket.id);

  // Chat Socket
  registerChatSocketHandlers(io, socket);

  // Video Calls
  videoSocketHandler(io, socket);

  socket.on("disconnect", () => {
    console.log("❌ User disconnected:", socket.id);
  });
});

const PORT = process.env.PORT || 4000;
server.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));

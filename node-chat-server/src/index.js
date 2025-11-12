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

// static uploads path
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const uploadsPath = path.join(__dirname, "uploads");
app.use("/uploads", express.static(uploadsPath));

// routes
app.use("/api", chatRoutes);

const server = http.createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

const onlineUsers = new Map();

// centralize socket handling
io.on("connection", (socket) => {
  chatSocketHandler(io, socket, onlineUsers);
  videoSocketHandler(io, socket);
});

const PORT = process.env.PORT || 4000;
server.listen(PORT, () => console.log(`🚀 Server running on port ${PORT}`));

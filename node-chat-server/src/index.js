import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";
import { connectDB } from "./config/db.js";
import chatRoutes from "./routes/chatRoutes.js";
import { registerSocketHandlers } from "./sockets/chatSocketHandler.js";

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

// ---------------- ROUTES ----------------
app.use("/api", chatRoutes);

// ---------------- SERVER & SOCKET SETUP ----------------
const server = http.createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

// Import and register socket logic
registerSocketHandlers(io);

const PORT = process.env.PORT || 4000;
server.listen(PORT, () =>
  console.log(`🚀 Server running on http://127.0.0.1:${PORT}`)
);

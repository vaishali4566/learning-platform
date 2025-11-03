import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";
import dotenv from "dotenv";
import { connectDB } from "./config/db.js";
import ChatRoom from "./models/ChatRoom.js";
import chatRoutes from "./routes/chatRoutes.js";
import Message from "./models/Message.js";

dotenv.config();
connectDB();

const app = express();
app.use(cors());
app.use(express.json());
app.use("/api", chatRoutes);

const server = http.createServer(app);
const io = new Server(server, {
  cors: { origin: "*" },
});

io.on("connection", (socket) => {
  console.log("🟢 New client connected:", socket.id);

  socket.on("join", ({ roomId }) => {
    socket.join(roomId);
    console.log(`✅ User joined chat room: ${roomId}`);
  });


  socket.on("sendMessage", async (data) => {
    try {
      const { roomId, sender, receiver, message } = data;

      if (!roomId || !message) {
        socket.emit("error", { message: "Missing roomId or message" });
        return;
      }

      // 💾 Save message in MongoDB
      const newMsg = await Message.create({
        roomId,
        sender,
        receiver,
        message,
      });

      // 📡 Send message to everyone in that room
      io.in(roomId).emit("newMessage", newMsg);

    } catch (err) {
      console.error("Error saving message:", err);
    }
  });



  socket.on("disconnect", () => {
    console.log("🔴 Client disconnected");
  });
});

const PORT = process.env.PORT || 4000;
server.listen(PORT, () => console.log(`🚀 Socket.IO running on port ${PORT}`));

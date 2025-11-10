// src/routes/chatRoutes.js
import express from "express";
import multer from "multer";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import ChatRoom from "../models/ChatRoom.js";
import Message from "../models/Message.js";

const router = express.Router();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const uploadPath = path.join(__dirname, "..", "uploads");

if (!fs.existsSync(uploadPath)) {
  fs.mkdirSync(uploadPath, { recursive: true });
  console.log("Created uploads folder at:", uploadPath);
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, uploadPath), 
  filename: (req, file, cb) => {
    const uniqueName = Date.now() + "-" + Math.round(Math.random() * 1E9) + "-" + file.originalname;
    cb(null, uniqueName);
  },
});

const upload = multer({ storage });

// CREATE ROOM
router.post("/create-room", async (req, res) => {
  try {
    const { participants } = req.body;
    if (!participants || !Array.isArray(participants)) {
      return res.status(400).json({ message: "Invalid participants" });
    }

    const normalized = participants.map(p => ({
      id: String(p.id),
      type: p.type
    }));

    const existing = await ChatRoom.findOne({
      participants: {
        $all: normalized.map(p => ({
          $elemMatch: { type: p.type, id: p.id }
        }))
      }
    });

    if (existing) return res.json({ room: existing });

    const room = await ChatRoom.create({ participants: normalized });
    res.status(201).json({ room });
  } catch (err) {
    console.error("Room Error:", err);
    res.status(500).json({ message: "Server error" });
  }
});

// GET MESSAGES
router.get("/messages/:roomId", async (req, res) => {
  try {
    const messages = await Message.find({ roomId: req.params.roomId }).sort({ createdAt: 1 });
    res.json({ messages });
  } catch (err) {
    console.error("Fetch Error:", err);
    res.status(500).json({ message: "Error fetching messages" });
  }
});

// SEND MESSAGE WITH FILE
router.post("/send", upload.single("file"), async (req, res) => {
  try {
    let sender, receiver;
    try {
      sender = req.body.sender ? JSON.parse(req.body.sender) : null;
      receiver = req.body.receiver ? JSON.parse(req.body.receiver) : null;
    } catch (e) {
      return res.status(400).json({ success: false, error: "Invalid sender/receiver JSON" });
    }

    const { roomId, message } = req.body;

    if (!roomId || !sender || !receiver) {
      return res.status(400).json({ success: false, error: "Missing required fields" });
    }

    if (!message && !req.file) {
      return res.status(400).json({ success: false, error: "Message or file required" });
    }

    const fileType = req.file ? req.file.mimetype.split("/")[0] === "image" ? "image" : "file" : null;

    const newMsg = await Message.create({
      roomId,
      sender: { id: String(sender.id), type: sender.type },
      receiver: { id: String(receiver.id), type: receiver.type },
      message: message || null,
      fileUrl: req.file ? `/uploads/${req.file.filename}` : null,
      fileType,
      seen: false,
    });

    res.status(201).json({ success: true, message: newMsg });
  } catch (err) {
    console.error("SEND MESSAGE ERROR:", err.message);
    res.status(500).json({ success: false, error: "Failed to save message" });
  }
});

export default router;
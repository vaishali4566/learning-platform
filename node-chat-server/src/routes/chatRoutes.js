import express from "express";
import multer from "multer";
import fs from "fs";
import ChatRoom from "../models/ChatRoom.js";
import Message from "../models/Message.js";

const router = express.Router();

// ✅ Ensure uploads folder exists
if (!fs.existsSync("uploads")) {
  fs.mkdirSync("uploads");
}

// 🗂️ Multer setup
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, "uploads/"),
  filename: (req, file, cb) => {
    const uniqueName = Date.now() + "-" + file.originalname;
    cb(null, uniqueName);
  },
});
const upload = multer({ storage });

// ✅ Create chat room
router.post("/create-room", async (req, res) => {
  try {
    const { participants } = req.body;

    const existingRoom = await ChatRoom.findOne({
      participants: {
        $all: participants.map((p) => ({
          $elemMatch: { type: p.type, id: p.id },
        })),
      },
    });

    if (existingRoom) {
      return res.json({ room: existingRoom });
    }

    const newRoom = await ChatRoom.create({ participants });
    res.status(201).json({ room: newRoom });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: "Error creating room" });
  }
});

// ✅ Get all messages of a room
router.get("/messages/:roomId", async (req, res) => {
  try {
    const messages = await Message.find({ roomId: req.params.roomId }).sort({
      createdAt: 1,
    });
    res.json({ messages });
  } catch (err) {
    console.error("Error fetching messages:", err);
    res.status(500).json({ message: "Error fetching messages" });
  }
});

// 📤 Send message with attachment
router.post("/send", upload.single("file"), async (req, res) => {
  try {
    const { roomId, sender, receiver, message } = req.body;

    if (!message && !req.file) {
      return res
        .status(400)
        .json({ success: false, error: "Message or file required" });
    }

    const newMsg = new Message({
      roomId,
      sender: JSON.parse(sender),
      receiver: JSON.parse(receiver),
      message,
      fileUrl: req.file ? `/uploads/${req.file.filename}` : null,
      fileType: req.file ? req.file.mimetype.split("/")[0] : null,
    });

    await newMsg.save();
    res.status(201).json({ success: true, message: newMsg });
  } catch (err) {
    console.error("❌ [Attachment Error]", err);
    res.status(500).json({ success: false, error: "Failed to send message" });
  }
});

export default router;

import express from "express";
import ChatRoom from "../models/ChatRoom.js";
import Message from "../models/Message.js";

const router = express.Router();

// ✅ Create chat room (called from Laravel)
router.post("/create-room", async (req, res) => {
  try {
    const { participants } = req.body;

    // check if room already exists
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
    const messages = await Message.find({ roomId: req.params.roomId }).sort({ createdAt: 1 });
    res.json({ messages });
  } catch (err) {
    console.error("Error fetching messages:", err);
    res.status(500).json({ message: "Error fetching messages" });
  }
});




export default router;

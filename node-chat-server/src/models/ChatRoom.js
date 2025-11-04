import mongoose from "mongoose";

const ChatRoomSchema = new mongoose.Schema(
  {
    participants: [
      {
        type: { type: String, enum: ["user", "trainer", "admin"], required: true },
        id: { type: Number, required: true }, // MySQL id reference
      },
    ],
  },
  { timestamps: true }
);

export default mongoose.model("ChatRoom", ChatRoomSchema);

import mongoose from "mongoose";

const MessageSchema = new mongoose.Schema(
  {
    roomId: { type: mongoose.Schema.Types.ObjectId, ref: "ChatRoom", required: true },
    sender: {
      type: { type: String, enum: ["user", "trainer", "admin"], required: true },
      id: { type: Number, required: true },
    },
    receiver: {
      type: { type: String, enum: ["user", "trainer", "admin"], required: true },
      id: { type: Number, required: true },
    },
    message: { type: String, required: true },
    fileUrl: { type: String }, // ✅ attachment URL
    fileType: { type: String }, // ✅ e.g. "image", "pdf", "docx"
    seen: { type: Boolean, default: false },
  },
  { timestamps: true }
);

export default mongoose.model("Message", MessageSchema);

// src/sockets/videoSocketHandler.js
export const videoSocketHandler = (io, socket) => {
  // User joins a video room
  socket.on("joinVideoRoom", (roomId) => {
    socket.join(roomId);
    console.log(`🎥 ${socket.id} joined video room: ${roomId}`);
  });

  // Handle WebRTC offer
  socket.on("offer", (data) => {
    const { roomId, offer } = data;
    console.log(`📡 Offer from ${socket.id} for room ${roomId}`);
    socket.to(roomId).emit("offer", { offer, from: socket.id });
  });

  // Handle WebRTC answer
  socket.on("answer", (data) => {
    const { roomId, answer } = data;
    console.log(`📡 Answer from ${socket.id} for room ${roomId}`);
    socket.to(roomId).emit("answer", { answer, from: socket.id });
  });

  // Handle ICE candidate exchange
  socket.on("ice-candidate", (data) => {
    const { roomId, candidate } = data;
    socket.to(roomId).emit("ice-candidate", { candidate, from: socket.id });
  });
};

// src/sockets/videoSocketHandler.js

export const videoSocketHandler = (io, socket) => {
  socket.on("joinVideoRoom", (roomId) => {
    socket.join(roomId);
    console.log(`🎥 Joined video room: ${roomId}`);
  });

  socket.on("offer", ({ roomId, offer }) => {
    socket.to(roomId).emit("offer", { offer, from: socket.id });
  });

  socket.on("answer", ({ roomId, answer }) => {
    socket.to(roomId).emit("answer", { answer, from: socket.id });
  });

  socket.on("ice-candidate", ({ roomId, candidate }) => {
    socket.to(roomId).emit("ice-candidate", { candidate, from: socket.id });
  });
};

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0A0E19] text-white flex flex-col items-center justify-center">
    <h1 class="text-2xl font-semibold mb-6">🎥 One-on-One Video Call</h1>

    <div class="flex gap-6 items-center">
        <div class="flex flex-col items-center">
            <h2 class="mb-2">You</h2>
            <video id="localVideo" autoplay muted playsinline class="w-72 h-48 rounded-lg border border-gray-600"></video>
        </div>

        <div class="flex flex-col items-center">
            <h2 class="mb-2">Other User</h2>
            <video id="remoteVideo" autoplay playsinline class="w-72 h-48 rounded-lg border border-gray-600"></video>
        </div>
    </div>

    <div class="mt-8 space-x-4">
        <button onclick="startCall()" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg">
            Start Call
        </button>
        <button onclick="endCall()" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-lg">
            End Call
        </button>
    </div>
</div>

{{-- Socket.IO --}}
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>

<script>
const socket = io("http://localhost:4000"); // your Node backend
const roomId = "1234"; // can be dynamic (e.g. your chat room id)

let localStream;
let peerConnection;

const servers = {
  iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

socket.emit("joinVideoRoom", roomId);

// ✅ Start camera and mic
async function startVideo() {
  try {
    localStream = await navigator.mediaDevices.getUserMedia({ video: false, audio: true });
    document.getElementById("localVideo").srcObject = localStream;

    peerConnection = new RTCPeerConnection(servers);

    // Add tracks to connection
    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

    // Show remote video
    peerConnection.ontrack = event => {
      document.getElementById("remoteVideo").srcObject = event.streams[0];
    };

    // Send ICE candidates to other peer
    peerConnection.onicecandidate = event => {
      if (event.candidate) {
        socket.emit("ice-candidate", { roomId, candidate: event.candidate });
      }
    };
  } catch (err) {
    console.error("❌ Camera/Mic error:", err);
    alert("Failed to access camera or microphone.");
  }
}

// ✅ Caller starts the call
async function startCall() {
  await startVideo();
  const offer = await peerConnection.createOffer();
  await peerConnection.setLocalDescription(offer);
  socket.emit("offer", { roomId, offer });
}

// ✅ Receiver gets the offer
socket.on("offer", async ({ offer }) => {
  if (!peerConnection) await startVideo();

  await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
  const answer = await peerConnection.createAnswer();
  await peerConnection.setLocalDescription(answer);
  socket.emit("answer", { roomId, answer });
});

// ✅ Caller receives the answer
socket.on("answer", async ({ answer }) => {
  await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
});

// ✅ Exchange ICE candidates
socket.on("ice-candidate", async ({ candidate }) => {
  try {
    await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
  } catch (e) {
    console.error("ICE candidate error:", e);
  }
});

// ✅ End call (stop tracks)
function endCall() {
  if (localStream) {
    localStream.getTracks().forEach(track => track.stop());
    localStream = null;
  }
  if (peerConnection) {
    peerConnection.close();
    peerConnection = null;
  }
  document.getElementById("localVideo").srcObject = null;
  document.getElementById("remoteVideo").srcObject = null;
  alert("Call ended.");
}
</script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact Form</title>
</head>
<body>
  <form id="contactForm" method="post">
    @csrf
    <input type="text" name="name" placeholder="Your Name" required><br>
    <input type="email" name="email" placeholder="Your Email" required><br>
    <textarea name="message" placeholder="Type your message..." required></textarea><br>
    <button type="submit">Send</button>
  </form>

  <script>
  document.querySelector("#contactForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(e.target));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    try {
      const res = await fetch("{{ route('send.to.telegram') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(formData)
      });

      const result = await res.json();

      if (res.ok) {
        alert("✅ Message sent successfully!");
      } else {
        alert("❌ Failed: " + (result.message || "Unknown error"));
      }
    } catch (error) {
      console.error(error);
      alert("⚠️ Error sending message: " + error);
    }
  });
  </script>
</body>
</html>

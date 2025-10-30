<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact Us</title>

<style>
  /* ===== GLOBAL STYLES ===== */
  * {
    box-sizing: border-box;
  }

  body {
    background: #101727; /* Base: Deep Night */
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    color: #E6EDF7;
    overflow-x: hidden;
  }

  main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  /* ===== FORM CONTAINER ===== */
  .form {
    width: 100%;
    max-width: 400px;
    background: linear-gradient(145deg, #1b2238, #141b2d);
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6), 0 0 25px rgba(0, 194, 255, 0.05);
    padding: 40px 40px 70px;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.8s ease forwards;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .form h2 {
    margin: 0 0 25px;
    color: #E6EDF7;
    border-bottom: 2px solid #3A6EA5; /* Accent Blue */
    text-align: center;
    letter-spacing: 1px;
    padding-bottom: 8px;
    font-size: 22px;
    text-transform: uppercase;
  }

  p {
    margin: 18px 0 0;
  }

  p:before {
    content: attr(type);
    display: block;
    font-size: 13px;
    color: #8A93A8;
    margin-bottom: 6px;
  }

  /* ===== INPUT & TEXTAREA ===== */
  input,
  textarea {
    width: 100%;
    padding: 10px;
    background: #1c1f31;
    outline: none;
    border: none;
    color: #E6EDF7;
    border-bottom: 2px solid #3A6EA5;
    font-size: 14px;
    transition: all 0.35s ease;
    border-radius: 4px 4px 0 0;
  }

  input::placeholder,
  textarea::placeholder {
    color: #9fa6bb;
  }

  input:focus,
  textarea:focus {
    border-color: #00C2FF;
    background: #151b2e;
    box-shadow: 0 0 8px rgba(0, 194, 255, 0.4);
  }

  textarea {
    min-height: 80px;
    resize: vertical;
  }

  /* Autofill Fix */
  input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px #1c1f31 inset !important;
    -webkit-text-fill-color: #E6EDF7 !important;
  }

  /* ===== BUTTON ===== */
  button {
    float: right;
    padding: 10px 18px;
    margin-top: 28px;
    background: transparent;
    border: 2px solid #00C2FF;
    color: #E6EDF7;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
  }

  button::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: #00C2FF;
    transition: all 0.4s ease;
    z-index: 0;
  }

  button:hover::before {
    left: 0;
  }

  button:hover {
    color: #101727;
  }

  button span {
    position: relative;
    z-index: 1;
  }

  /* ===== INFO BOX ===== */
  .info-box {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #3A6EA5;
    color: #E6EDF7;
    padding: 12px;
    font-size: 13px;
    text-align: center;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    display: flex;
    justify-content: center;
    gap: 15px;
    align-items: center;
    box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.3);
    animation: slideUp 1s ease forwards;
  }

  @keyframes slideUp {
    from {
      transform: translateY(30px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .info-box span {
    color: #E6EDF7;
  }

  /* ===== FOOTER ===== */
  footer {
    background: linear-gradient(to right, #171717, #101727);
    color: #b8b8b8;
    text-align: center;
    padding: 18px 0;
    font-size: 0.9rem;
    letter-spacing: 0.4px;
    border-top: 1px solid #222;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.5);
    transition: background 0.5s ease;
  }

  footer:hover {
    background: linear-gradient(to right, #101727, #171717);
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 480px) {
    .form {
      padding: 30px 25px 70px;
    }

    button {
      width: 100%;
      float: none;
    }

    .info-box {
      flex-direction: column;
      gap: 6px;
      padding: 10px;
      font-size: 12px;
    }
  }
</style>
  

</head>

<body>
  <main>
    <form class="form" id="contactForm" method="POST">
      @csrf
      <h2>CONTACT US</h2>
      <p type="Name:"><input type="text" name="name" placeholder="Write your name here.." required></p>
      <p type="Email:"><input type="email" name="email" placeholder="Let us know how to contact you back.." required></p>
      <p type="Message:"><textarea name="message" placeholder="What would you like to tell us.." required></textarea></p>
      <button type="submit">Send Message</button>

      <div class="info-box">
        <span class="fa fa-phone"></span> 001 1023 567
        <span class="fa fa-envelope-o"></span> contact@company.com
      </div>
    </form>
  </main>

  <footer>
    &copy; {{ date('Y') }} Learning Platform. All rights reserved.
  </footer>

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
          e.target.reset();
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
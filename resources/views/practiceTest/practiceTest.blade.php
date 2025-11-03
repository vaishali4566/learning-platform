<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Practice Test</title>

  <!-- Modern Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #0b111e, #101727, #182133);
      color: #e0e0e0;
      font-family: 'Poppins', sans-serif;
      padding: 30px;
      margin: 0;
      min-height: 100vh;
    }

    h1 {
      color: #00bcd4;
      text-align: center;
      margin-bottom: 30px;
      letter-spacing: 1px;
    }

    .header {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      margin-bottom: 40px;
    }

    .copy-btn {
      background-color: #00bcd4;
      color: #101727;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.25s;
      box-shadow: 0 0 10px rgba(0, 188, 212, 0.3);
    }

    .copy-btn:hover {
      background-color: #03a9f4;
      box-shadow: 0 0 15px rgba(0, 188, 212, 0.6);
      transform: scale(1.03);
    }

    .question {
      margin-bottom: 30px;
      background: rgba(24, 33, 51, 0.95);
      border-radius: 14px;
      padding: 25px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .question:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 188, 212, 0.1);
    }

    .option {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 15px;
      margin-top: 10px;
      border: 1px solid #2c3e50;
      border-radius: 8px;
      background-color: #151c2e;
      cursor: pointer;
      transition: 0.25s ease;
    }

    .option:hover {
      background-color: #1c2a4a;
      box-shadow: 0 0 6px rgba(0, 188, 212, 0.3);
    }

    .option input {
      accent-color: #00bcd4;
      cursor: pointer;
      transform: scale(1.2);
    }

    .correct {
      background-color: #2e7d32 !important;
      border-color: #2e7d32;
      color: #fff;
      transition: background-color 0.3s ease;
    }

    .wrong {
      background-color: #c62828 !important;
      border-color: #c62828;
      color: #fff;
      transition: background-color 0.3s ease;
    }

    .result-icon {
      margin-left: auto;
      font-weight: bold;
      font-size: 18px;
      transform: scale(0);
      transition: transform 0.2s ease;
    }

    .option.correct .result-icon,
    .option.wrong .result-icon {
      transform: scale(1);
    }

    /* Loading Spinner */
    .loader {
      border: 4px solid #1a263b;
      border-top: 4px solid #00bcd4;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      margin: 100px auto;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      100% { transform: rotate(360deg); }
    }

  </style>
</head>

<body>
  <div class="header">
    <h1>Practice Test</h1>
    <button class="copy-btn" onclick="copyURL()">Copy Current URL</button>
  </div>

  <div id="test-area">
    <div class="loader"></div>
  </div>

  <script>
    async function loadQuestions() {
      try {
        const response = await fetch("{{ route('practice.questions') }}");
        const questions = await response.json();

        const container = document.getElementById("test-area");
        container.innerHTML = "";

        questions.forEach((q, index) => {
          const questionDiv = document.createElement("div");
          questionDiv.classList.add("question");

          const title = document.createElement("h3");
          title.textContent = `${index + 1}. ${q.question}`;
          questionDiv.appendChild(title);

          q.options.forEach((opt, i) => {
            const optionDiv = document.createElement("label");
            optionDiv.classList.add("option");

            const radio = document.createElement("input");
            radio.type = "radio";
            radio.name = `question_${q.id}`;
            radio.value = opt;

            const text = document.createElement("span");
            text.textContent = opt;

            const icon = document.createElement("span");
            icon.classList.add("result-icon");
            icon.textContent = "✔"; // will change dynamically

            radio.onclick = () => {
              // disable other options
              const allOptions = questionDiv.querySelectorAll(".option");
              allOptions.forEach(o => o.style.pointerEvents = "none");

              if (opt === q.correct_answer) {
                optionDiv.classList.add("correct");
                icon.textContent = "✔";
              } else {
                optionDiv.classList.add("wrong");
                icon.textContent = "✖";
                // mark correct answer too
                const correctOpt = Array.from(allOptions)
                  .find(o => o.textContent.trim().startsWith(q.correct_answer));
                if (correctOpt) {
                  correctOpt.classList.add("correct");
                  correctOpt.querySelector(".result-icon").textContent = "✔";
                }
              }
            };

            optionDiv.appendChild(radio);
            optionDiv.appendChild(text);
            optionDiv.appendChild(icon);
            questionDiv.appendChild(optionDiv);
          });

          container.appendChild(questionDiv);
        });
      } catch (error) {
        console.error("Error loading questions:", error);
        document.getElementById("test-area").innerHTML = "<p>Failed to load questions.</p>";
      }
    }

    function copyURL() {
      navigator.clipboard.writeText(window.location.href)
        .then(() => alert("✅ URL copied to clipboard!"))
        .catch(() => alert("❌ Failed to copy URL."));
    }

    loadQuestions();
  </script>
</body>
</html>

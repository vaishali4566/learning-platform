<div x-data="{ open: false }" class="fixed bottom-5 right-5 z-50">

  <!-- Floating Chat Button -->
  <button id="chat-toggle-btn"
    class="bg-gradient-to-r from-indigo-500 via-blue-500 to-sky-400 text-white w-14 h-14 rounded-full p-1 shadow-xl hover:scale-110 transition-transform duration-300 fixed bottom-8 right-8 z-50 flex items-center justify-center animate-pulse-slow">
    <i class="bi bi-chat-dots-fill text-xl"></i>
  </button>

  <!-- Chat Box -->
  <div id="chat-box"
    class="hidden fixed bottom-24 right-5 w-[360px] h-[520px] min-w-[280px] min-h-[320px]
           bg-gradient-to-b from-indigo-100 via-sky-50 to-white border border-gray-200 rounded-[2rem] shadow-2xl flex flex-col overflow-hidden transition-all duration-300 backdrop-blur-xl">

    <!-- Header -->
    <div id="chat-header"
      class="bg-gradient-to-r from-indigo-500 via-blue-500 to-sky-400 text-white px-5 py-3 flex justify-between items-center shadow-md rounded-t-[2rem]">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
          <i class="bi bi-stars text-lg"></i>
        </div>
        <div class="flex flex-col leading-tight">
          <span class="font-semibold text-base">Gemini Chat</span>
          <span class="text-xs text-white/80">Online</span>
        </div>
      </div>
      <button id="chat-close-btn"
        onclick="document.getElementById('chat-box').classList.add('hidden')"
        class="hover:text-gray-200 text-lg transition">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <!-- Messages -->
    <div id="chat-messages"
      class="flex-1 p-4 overflow-y-auto space-y-4 bg-transparent"
      style="scroll-behavior: smooth;">
    </div>

    <!-- Input -->
    <div class="border-t border-gray-200 p-3 bg-white/90 flex items-center gap-2 backdrop-blur-md rounded-b-[2rem]">
      <!-- 📎 Attach -->
      <button id="chat-attach-btn"
        class="text-gray-500 hover:text-blue-600 transition text-lg flex items-center justify-center">
        <i class="bi bi-paperclip"></i>
      </button>

      <!-- ✏️ Input -->
      <input id="chat-input"
        class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition placeholder-gray-400"
        placeholder="Type a message..." />

      <!-- 📤 Send -->
      <button id="chat-send-btn"
        class="bg-gradient-to-r from-indigo-500 via-blue-500 to-sky-400 text-white px-4 py-2 rounded-full hover:opacity-90 transition flex items-center gap-1 shadow-md">
        <i class="bi bi-send-fill"></i>
      </button>
    </div>

    <!-- Resize Handle -->
    <div id="resize-handle"
      class="absolute bottom-0 right-0 w-4 h-4 cursor-se-resize bg-transparent"></div>
  </div>

  <!-- Script (Logic Unchanged) -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('chat-toggle-btn');
      const chatBox = document.getElementById('chat-box');
      const sendBtn = document.getElementById('chat-send-btn');
      const input = document.getElementById('chat-input');
      const messagesDiv = document.getElementById('chat-messages');
      const header = document.getElementById('chat-header');
      const resizeHandle = document.getElementById('resize-handle');
      const STORAGE_KEY = 'gemini_chat_history';
      const SCROLL_KEY = 'gemini_chat_scroll_frac_v2';
      let isRestoring = false;

      const savedMessages = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
      savedMessages.forEach(msg => appendMessage(msg.from, msg.text, false));

      toggleBtn.addEventListener('click', () => chatBox.classList.toggle('hidden'));
      sendBtn.addEventListener('click', sendMessage);
      input.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });

      function appendMessage(from, text, save = true) {
        const msgDiv = document.createElement('div');
        msgDiv.className = from === 'user' ? 'text-right flex justify-end' : 'text-left flex justify-start';
        const paragraphs = text.split(/\n+/).map(p => `<p class="mb-1">${p.trim()}</p>`).join('');
        msgDiv.innerHTML = `
          <div class="${from === 'user'
            ? 'bg-gradient-to-r from-indigo-500 via-blue-500 to-sky-400 text-white'
            : 'bg-white/80 border border-gray-200 text-gray-800 shadow-sm'} 
            inline-block px-4 py-2 rounded-2xl max-w-[80%] mb-1 break-words text-sm leading-relaxed backdrop-blur-md">
            ${paragraphs}
          </div>
        `;
        messagesDiv.appendChild(msgDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        if (save) {
          savedMessages.push({ from, text });
          localStorage.setItem(STORAGE_KEY, JSON.stringify(savedMessages));
        }
      }

      function sendMessage() {
        const userText = input.value.trim();
        if (!userText) return;
        appendMessage('user', userText);
        input.value = '';
        appendMessage('bot', '💭 Gemini is thinking...', false);
        const loadingMsg = messagesDiv.lastChild;

        fetch('{{ route("chatbot.send") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ message: userText })
        })
          .then(res => res.json())
          .then(data => {
            loadingMsg.remove();
            const botReply = data.reply || data.message || data.response || 'Sorry, I couldn’t understand that.';
            appendMessage('bot', botReply);
          })
          .catch(() => {
            loadingMsg.remove();
            appendMessage('bot', '⚠️ Error connecting to Gemini API.');
          });
      }

      // scroll + drag + resize logic (unchanged)
      function clamp(n, min, max) { return Math.max(min, Math.min(max, n)); }
      function saveScroll() {
        const maxScroll = Math.max(0, messagesDiv.scrollHeight - messagesDiv.clientHeight);
        if (maxScroll <= 0) return;
        const frac = messagesDiv.scrollTop / maxScroll;
        localStorage.setItem(SCROLL_KEY, JSON.stringify({ frac }));
      }
      function restoreScroll() {
        isRestoring = true;
        const saved = JSON.parse(localStorage.getItem(SCROLL_KEY) || 'null');
        const isReload = performance.navigation.type === performance.navigation.TYPE_RELOAD;
        function scrollBottom() {
          messagesDiv.scrollTop = messagesDiv.scrollHeight;
          isRestoring = false;
        }
        function scrollSaved() {
          if (!saved) return scrollBottom();
          const maxScroll = Math.max(0, messagesDiv.scrollHeight - messagesDiv.clientHeight);
          const target = Math.round(clamp(saved.frac || 1, 0, 1) * maxScroll);
          messagesDiv.scrollTop = clamp(target, 0, maxScroll);
          isRestoring = false;
        }
        setTimeout(() => {
          if (isReload) scrollBottom();
          else scrollSaved();
        }, 100);
      }
      messagesDiv.addEventListener('scroll', () => {
        if (isRestoring) return;
        clearTimeout(messagesDiv._scrollSaveTimer);
        messagesDiv._scrollSaveTimer = setTimeout(saveScroll, 150);
      });
      restoreScroll();

      // let offsetX, offsetY, isDragging = false;
      // header.addEventListener('mousedown', e => {
      //   isDragging = true;
      //   const rect = chatBox.getBoundingClientRect();
      //   offsetX = e.clientX - rect.left;
      //   offsetY = e.clientY - rect.top;
      //   document.body.style.userSelect = 'none';
      // });
      // window.addEventListener('mousemove', e => {
      //   if (!isDragging) return;
      //   chatBox.style.left = `${e.clientX - offsetX}px`;
      //   chatBox.style.top = `${e.clientY - offsetY}px`;
      //   chatBox.style.right = 'auto';
      //   chatBox.style.bottom = 'auto';
      // });
      // window.addEventListener('mouseup', () => {
      //   isDragging = false;
      //   document.body.style.userSelect = 'auto';
      // });

      let isResizing = false, startX, startY, startWidth, startHeight;
      resizeHandle.addEventListener('mousedown', e => {
        e.preventDefault();
        isResizing = true;
        startX = e.clientX;
        startY = e.clientY;
        const rect = chatBox.getBoundingClientRect();
        startWidth = rect.width;
        startHeight = rect.height;
        document.body.style.userSelect = 'none';
      });
      window.addEventListener('mousemove', e => {
        if (!isResizing) return;
        const newWidth = Math.max(280, startWidth + (e.clientX - startX));
        const newHeight = Math.max(320, startHeight + (e.clientY - startY));
        chatBox.style.width = `${newWidth}px`;
        chatBox.style.height = `${newHeight}px`;
      });
      window.addEventListener('mouseup', () => {
        isResizing = false;
        document.body.style.userSelect = 'auto';
      });
    });
  </script>

  <!-- Styling -->
  <style>
    @keyframes pulse-slow {
      0%, 100% { transform: scale(1); box-shadow: 0 0 0 rgba(59, 130, 246, 0.5); }
      50% { transform: scale(1.08); box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); }
    }
    .animate-pulse-slow {
      animation: pulse-slow 2.5s infinite;
    }

    #chat-toggle-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 0 25px rgba(59, 130, 246, 0.5);
    }

    /* Rounded smooth scrollbar */
    #chat-messages::-webkit-scrollbar {
      width: 6px;
    }
    #chat-messages::-webkit-scrollbar-thumb {
      background: rgba(59, 130, 246, 0.4);
      border-radius: 10px;
    }
    #chat-messages::-webkit-scrollbar-thumb:hover {
      background: rgba(59, 130, 246, 0.6);
    }
  </style>
</div>

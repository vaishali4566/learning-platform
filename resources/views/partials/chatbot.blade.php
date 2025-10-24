<div x-data="{ open: false }" class="fixed bottom-5 right-5 z-50">

    <!-- Floating Button -->
    <button id="chat-toggle-btn"
        class="bg-blue-600 text-white rounded-full p-4 shadow-xl hover:bg-blue-700 transition fixed bottom-5 right-5 z-50">
        💬
    </button>

    <!-- Chat Box -->
    <div id="chat-box"
        class="hidden fixed bottom-20 right-5 w-80 h-[500px] min-w-[260px] min-h-[300px]
               bg-white border border-gray-200 rounded-2xl shadow-2xl flex flex-col overflow-hidden z-50">

        <!-- Header (drag handle) -->
        <div id="chat-header"
            class="cursor-move bg-blue-600 text-white px-4 py-2 flex justify-between items-center">
            <span class="font-semibold">Gemini Chat</span>
            <button id="chat-close-btn"
                onclick="document.getElementById('chat-box').classList.add('hidden')"
                class="hover:text-gray-200">✖️</button>
        </div>

        <!-- Messages -->
        <div id="chat-messages"
            class="flex-1 p-3 overflow-y-auto space-y-2 bg-gray-50"
            style="scroll-behavior: smooth;">
        </div>

        <!-- Input -->
        <div class="border-t p-2 flex bg-white flex-shrink-0">
            <input id="chat-input"
                class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Type a message..." />
            <button id="chat-send-btn"
                class="bg-blue-600 text-white px-4 rounded-r-md hover:bg-blue-700 transition">Send</button>
        </div>

        <!-- Resize Handle -->
        <div id="resize-handle"
            class="absolute bottom-0 right-0 w-4 h-4 cursor-se-resize bg-transparent"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // --- Load saved messages ---
            const savedMessages = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            savedMessages.forEach(msg => appendMessage(msg.from, msg.text, false));

            toggleBtn.addEventListener('click', () => chatBox.classList.toggle('hidden'));
            sendBtn.addEventListener('click', sendMessage);
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') sendMessage();
            });

            // --- Append message ---
            function appendMessage(from, text, save = true) {
                const msgDiv = document.createElement('div');
                msgDiv.className = from === 'user' ? 'text-right' : 'text-left';
                const paragraphs = text.split(/\n+/).map(p => `<p class="mb-1">${p.trim()}</p>`).join('');
                msgDiv.innerHTML = `
                    <div class="${from === 'user'
                        ? 'bg-blue-500 text-white'
                        : 'bg-gray-200 text-gray-800'} inline-block px-3 py-2 rounded-lg max-w-[80%] mb-1 break-words text-sm leading-relaxed">
                        ${paragraphs}
                    </div>
                `;
                messagesDiv.appendChild(msgDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;

                if (save) {
                    savedMessages.push({
                        from,
                        text
                    });
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(savedMessages));
                }
            }

            // --- Send message ---
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
                        body: JSON.stringify({
                            message: userText
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        loadingMsg.remove();
                        appendMessage('bot', data.reply || 'Sorry, I couldn’t understand that.');
                    })
                    .catch(() => {
                        loadingMsg.remove();
                        appendMessage('bot', '⚠️ Error connecting to Gemini API.');
                    });
            }

            // --- Scroll persistence ---
            function clamp(n, min, max) {
                return Math.max(min, Math.min(max, n));
            }

            function saveScroll() {
                const maxScroll = Math.max(0, messagesDiv.scrollHeight - messagesDiv.clientHeight);
                if (maxScroll <= 0) return;
                const frac = messagesDiv.scrollTop / maxScroll;
                localStorage.setItem(SCROLL_KEY, JSON.stringify({
                    frac
                }));
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

            // --- Draggable header ---
            let offsetX, offsetY, isDragging = false;
            header.addEventListener('mousedown', e => {
                isDragging = true;
                const rect = chatBox.getBoundingClientRect();
                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;
                document.body.style.userSelect = 'none';
            });
            window.addEventListener('mousemove', e => {
                if (!isDragging) return;
                chatBox.style.left = `${e.clientX - offsetX}px`;
                chatBox.style.top = `${e.clientY - offsetY}px`;
                chatBox.style.right = 'auto';
                chatBox.style.bottom = 'auto';
            });
            window.addEventListener('mouseup', () => {
                isDragging = false;
                document.body.style.userSelect = 'auto';
            });

            // --- Resizable corner handle ---
            let isResizing = false,
                startX, startY, startWidth, startHeight;
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
                const newWidth = Math.max(260, startWidth + (e.clientX - startX));
                const newHeight = Math.max(300, startHeight + (e.clientY - startY));
                chatBox.style.width = `${newWidth}px`;
                chatBox.style.height = `${newHeight}px`;
            });
            window.addEventListener('mouseup', () => {
                isResizing = false;
                document.body.style.userSelect = 'auto';
            });
        });
    </script>
</div>
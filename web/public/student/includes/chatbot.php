<!-- Chatbot Widget -->
<div id="ai-chatbot" class="chat-widget-container">
    <!-- Chat Window -->
    <div id="chat-window" class="chat-window hidden">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar-small">
                    <i class="ri-robot-2-line"></i>
                </div>
                <div>
                    <h4 class="chat-title">EduFlip AI</h4>
                    <span class="chat-status"><span class="status-dot"></span> Online</span>
                </div>
            </div>
            <button onclick="toggleChat()" class="chat-close-btn"><i class="ri-close-line"></i></button>
        </div>
        
        <!-- Messages Area -->
        <div id="chat-messages" class="chat-messages">
            <!-- Welcome Message -->
            <div class="message-row ai">
                <div class="message-avatar">
                   <i class="ri-robot-2-line"></i>
                </div>
                <div class="message-bubble">
                    Hi! I'm your AI learning assistant. Ask me anything about your course! 🤖
                </div>
            </div>
        </div>
        
        <!-- Input Area -->
        <div class="chat-input-area">
            <form onsubmit="handleChatSubmit(event)" class="chat-form">
                <input type="text" id="chat-input" class="chat-input" placeholder="Ask a question..." autocomplete="off">
                <button type="submit" class="chat-send-btn"><i class="ri-send-plane-fill"></i></button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button onclick="toggleChat()" id="chat-toggle-btn" class="chat-toggle-btn">
        <i class="ri-chat-smile-2-line"></i>
    </button>
</div>

<style>
    /* Self-contained Chatbot Styles */
    .chat-widget-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        font-family: var(--font-main, sans-serif);
    }

    .chat-window {
        width: 320px;
        height: 400px;
        background: #1e293b; /* Slate 800 */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
        transform-origin: bottom right;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .chat-window.hidden {
        display: none;
        opacity: 0;
        transform: scale(0.9) translateY(20px);
        pointer-events: none;
    }
    
    .chat-window.visible {
        display: flex;
        opacity: 1;
        transform: scale(1) translateY(0);
        pointer-events: auto;
    }

    .chat-header {
        padding: 12px 16px;
        background: rgba(32, 178, 170, 0.1); /* Secondary color tint */
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(5px);
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .chat-avatar-small {
        width: 32px;
        height: 32px;
        background: #20B2AA; /* Secondary */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }
    
    .chat-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: white;
    }
    
    .chat-status {
        font-size: 0.75rem;
        color: #10b981; /* Success */
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-dot {
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
    }

    .chat-close-btn {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 4px;
        transition: color 0.2s;
    }
    .chat-close-btn:hover { color: white; }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: rgba(0,0,0,0.2);
    }
    
    /* Scrollbar */
    .chat-messages::-webkit-scrollbar { width: 6px; }
    .chat-messages::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    .chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }

    .message-row {
        display: flex;
        gap: 8px;
        max-width: 85%;
        animation: fadeIn 0.3s ease-out;
    }
    
    .message-row.ai { align-self: flex-start; }
    .message-row.user { align-self: flex-end; flex-direction: row-reverse; }

    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.8rem;
        color: white;
    }
    .ai .message-avatar { background: #20B2AA; }
    .user .message-avatar { background: #0F52BA; } /* Primary */

    .message-bubble {
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 0.9rem;
        line-height: 1.4;
        color: #f1f5f9;
    }
    
    .ai .message-bubble { background: rgba(255,255,255,0.1); border-top-left-radius: 2px; }
    .user .message-bubble { background: #0F52BA; border-top-right-radius: 2px; }

    .chat-input-area {
        padding: 10px;
        background: rgba(15, 23, 42, 0.4);
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    
    .chat-form { display: flex; gap: 8px; }
    
    .chat-input {
        flex: 1;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 8px 12px;
        color: white;
        font-size: 0.9rem;
    }
    .chat-input:focus { outline: none; border-color: #20B2AA; }
    
    .chat-send-btn {
        background: #20B2AA;
        border: none;
        border-radius: 6px;
        width: 36px;
        color: #0f172a;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .chat-send-btn:hover { background: #1aa199; }

    .chat-toggle-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #20B2AA, #0F52BA);
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(32, 178, 170, 0.4);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chat-toggle-btn:hover { transform: scale(1.1); }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
     /* Simulating typing animation */
    .typing-dots { display: flex; gap: 4px; padding: 4px 0; }
    .typing-dot {
        width: 6px; height: 6px; background: rgba(255,255,255,0.5); border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
</style>

<script>
    function toggleChat() {
        const window = document.getElementById('chat-window');
        const btn = document.getElementById('chat-toggle-btn');
        const isHidden = window.classList.contains('hidden');
        
        if (isHidden) {
            window.classList.remove('hidden');
            // Small delay for animation
            requestAnimationFrame(() => window.classList.add('visible'));
            btn.innerHTML = '<i class="ri-close-line"></i>';
        } else {
            window.classList.remove('visible');
            setTimeout(() => window.classList.add('hidden'), 300);
            btn.innerHTML = '<i class="ri-chat-smile-2-line"></i>';
        }
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const msg = input.value.trim();
        if (!msg) return;

        // User Message
        addMessage(msg, 'user');
        input.value = '';

        // Simulate AI Typing
        const loadingId = addLoading();
        
        // Mock Response
        setTimeout(() => {
            removeLoading(loadingId);
            const response = getMockAIResponse(msg);
            addMessage(response, 'ai');
        }, 1500);
    }

    function addMessage(text, type) {
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = `message-row ${type}`;
        
        const avatar = `<div class="message-avatar"><i class="ri-${type === 'ai' ? 'robot-2' : 'user'}-line"></i></div>`;
        const bubble = `<div class="message-bubble">${text}</div>`;
        
        div.innerHTML = type === 'ai' ? avatar + bubble : bubble + avatar;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }
    
    function addLoading() {
        const id = 'loading-' + Date.now();
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.id = id;
        div.className = 'message-row ai';
        div.innerHTML = `
            <div class="message-avatar"><i class="ri-robot-2-line"></i></div>
            <div class="message-bubble">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
        return id;
    }
    
    function removeLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function getMockAIResponse(msg) {
        msg = msg.toLowerCase();
        if (msg.includes('hello') || msg.includes('hi')) return "Hello! Ready to learn something new today?";
        if (msg.includes('html')) return "HTML (HyperText Markup Language) is the standard markup language for documents designed to be displayed in a web browser.";
        if (msg.includes('php')) return "PHP is a popular general-purpose scripting language that is especially suited to web development.";
        if (msg.includes('help')) return "I can explain course topics, help with quizzes, or guide you through the materials. Just ask!";
        if (msg.includes('quiz')) return "You can access your quizzes from the 'My Quizzes' section in the sidebar. Good luck!";
        return "That's an interesting question! Based on your current course material, I'd suggest checking the 'Introduction' module for more details.";
    }
</script>

@extends('layouts.app')

@section('title', 'AI Playground Chat')

@push('styles')
<style>
    .ai-playground {
        height: calc(100vh - 140px);
        background: #fff;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }
    
    .playground-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FAFAFA;
        border-radius: 12px 12px 0 0;
    }
    
    .playground-chat {
        flex-grow: 1;
        padding: 30px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .chat-bubble {
        display: flex;
        gap: 15px;
        max-width: 80%;
    }
    
    .chat-bubble.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    
    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .avatar-ai {
        background: var(--primary-light);
        color: var(--primary);
    }
    
    .avatar-user {
        background: #E2E8F0;
        color: #475569;
    }
    
    .chat-content {
        background: #F8FAFC;
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .user .chat-content {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    
    .chat-debug {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 5px;
        display: flex;
        gap: 15px;
    }
    
    .user .chat-debug {
        justify-content: flex-end;
    }
    
    .playground-input {
        padding: 20px;
        border-top: 1px solid var(--border-color);
        background: #fff;
        border-radius: 0 0 12px 12px;
    }
    
    .input-wrapper {
        display: flex;
        gap: 10px;
        background: #F8FAFC;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 10px 15px;
        align-items: center;
    }
    
    .input-wrapper input {
        flex-grow: 1;
        border: none;
        background: transparent;
        padding: 5px;
    }
    
    .input-wrapper input:focus {
        outline: none;
    }
</style>
@endpush

@section('content')
<div class="ai-playground">
    <div class="playground-header">
        <div>
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-flask text-primary me-2"></i> AI Playground</h5>
            <small class="text-muted">Test how your AI responds to customers before putting it live.</small>
        </div>
        <div>
            <span class="badge bg-soft-success text-success border border-success-subtle px-3 py-2">
                <i class="fa-solid fa-circle-check me-1"></i> GPT-4o Online
            </span>
            <button class="btn btn-sm btn-outline-secondary ms-2"><i class="fa-solid fa-rotate-right"></i> Reset Context</button>
        </div>
    </div>
    
    <div class="playground-chat" id="chatBox">
        
        <div class="text-center my-2">
            <span class="badge bg-light text-muted rounded-pill px-3">Context Loaded: Business Profile, FAQ, Pricing</span>
        </div>
        
        <div class="chat-bubble user">
            <div class="chat-avatar avatar-user">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <div class="chat-content">
                    Hi, do you build eCommerce websites? How much do they cost?
                </div>
                <div class="chat-debug">
                    <span>Simulating: Facebook Messenger</span>
                </div>
            </div>
        </div>
        
        <div class="chat-bubble ai">
            <div class="chat-avatar avatar-ai">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div>
                <div class="chat-content">
                    Hello! 👋 Yes, we absolutely build eCommerce websites at Atomation Digital. Our standard eCommerce package starts at **20,000 BDT**. 
                    <br><br>
                    Would you like to know what features are included in this package?
                </div>
                <div class="chat-debug">
                    <span><i class="fa-solid fa-bolt text-warning"></i> Intent: Pricing</span>
                    <span><i class="fa-solid fa-bullseye text-success"></i> Confidence: 98%</span>
                    <span><i class="fa-solid fa-stopwatch text-info"></i> Latency: 1.2s</span>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="playground-input">
        <div class="input-wrapper">
            <input type="text" id="chatInput" placeholder="Type a message to test the AI...">
            <button id="sendBtn" class="btn btn-primary rounded-circle"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
        <div class="text-center mt-2">
            <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Messages sent here are not saved to the database and do not consume credits.</small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const chatBox = document.getElementById('chatBox');

        function appendUserMessage(text) {
            const html = `
            <div class="chat-bubble user" style="animation: fadeIn 0.3s ease;">
                <div class="chat-avatar avatar-user">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <div class="chat-content">${text}</div>
                    <div class="chat-debug"><span>Simulating: Web Chat</span></div>
                </div>
            </div>`;
            chatBox.insertAdjacentHTML('beforeend', html);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function appendAIMessage(userText) {
            // Add typing indicator
            const typingId = 'typing-' + Date.now();
            const typingHtml = `
            <div id="${typingId}" class="chat-bubble ai" style="animation: fadeIn 0.3s ease;">
                <div class="chat-avatar avatar-ai"><i class="fa-solid fa-robot"></i></div>
                <div>
                    <div class="chat-content"><i class="fa-solid fa-ellipsis fa-fade"></i> AI is thinking...</div>
                </div>
            </div>`;
            chatBox.insertAdjacentHTML('beforeend', typingHtml);
            chatBox.scrollTop = chatBox.scrollHeight;

            // Simulate network delay then respond
            setTimeout(() => {
                document.getElementById(typingId).remove();
                
                const textLower = userText.toLowerCase();
                let response = "That's an interesting question! Since I am currently in simulation mode without a live API key, I don't have the full context yet. But I am ready to assist once activated!";
                let intent = "General";
                let confidence = "75%";
                
                if (textLower.includes("price") || textLower.includes("cost") || textLower.includes("koto") || textLower.includes("taka") || textLower.includes("how much")) {
                    response = "Our eCommerce packages start at 20,000 BDT, while social media marketing starts at 15,000 BDT/month. Would you like a detailed breakdown?";
                    intent = "Pricing Inquiry";
                    confidence = "98%";
                } else if (textLower.includes("hello") || textLower.includes("hi") || textLower.includes("hey")) {
                    response = "Hello there! Welcome to Atomation Digital. How can I help you grow your business today?";
                    intent = "Greeting";
                    confidence = "99%";
                } else if (textLower.includes("location") || textLower.includes("address") || textLower.includes("where") || textLower.includes("kothay") || textLower.includes("office")) {
                    response = "Our physical office is located at Banani, Road 11, Dhaka. You can visit us from Sunday to Thursday (10 AM to 6 PM).";
                    intent = "Location Inquiry";
                    confidence = "95%";
                } else if (textLower.includes("human") || textLower.includes("real person") || textLower.includes("admin")) {
                    response = "I understand. Let me hand this conversation over to a human admin who will assist you shortly.";
                    intent = "Human Handoff";
                    confidence = "100%";
                } else if (textLower.includes("service") || textLower.includes("ki kor") || textLower.includes("what do you do") || textLower.includes("offer")) {
                    response = "We offer Web Development, Social Media Marketing, and AI Chatbot solutions. Which one are you interested in?";
                    intent = "Service Inquiry";
                    confidence = "92%";
                }
                
                const html = `
                <div class="chat-bubble ai" style="animation: fadeIn 0.3s ease;">
                    <div class="chat-avatar avatar-ai"><i class="fa-solid fa-robot"></i></div>
                    <div>
                        <div class="chat-content">${response}</div>
                        <div class="chat-debug">
                            <span><i class="fa-solid fa-bolt text-warning"></i> Intent: ${intent}</span>
                            <span><i class="fa-solid fa-bullseye text-success"></i> Confidence: ${confidence}</span>
                        </div>
                    </div>
                </div>`;
                chatBox.insertAdjacentHTML('beforeend', html);
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 1000);
        }

        function handleSend() {
            const text = input.value.trim();
            if(text === '') return;
            
            appendUserMessage(text);
            input.value = '';
            
            appendAIMessage(text);
        }

        sendBtn.addEventListener('click', handleSend);
        input.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') handleSend();
        });
    });
</script>
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection

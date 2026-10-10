@extends('layouts.app')

@section('title', 'AI Playground Chat')

@push('styles')
<style>
    .ai-playground {
        height: calc(100vh - 140px);
        background: #fff;
        border-radius: 16px;
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
        border-radius: 16px 16px 0 0;
    }
    
    .playground-chat {
        flex-grow: 1;
        padding: 30px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: #F8FAFC;
    }
    
    .chat-bubble {
        display: flex;
        gap: 15px;
        max-width: 85%;
    }
    
    .chat-bubble.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    
    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    
    .avatar-ai {
        background: #EEF2FF;
        color: #4F46E5;
    }
    
    .avatar-user {
        background: #E2E8F0;
        color: #475569;
    }
    
    .chat-content {
        background: #ffffff;
        padding: 15px 20px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        font-size: 0.95rem;
        line-height: 1.6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    
    .user .chat-content {
        background: #4F46E5;
        color: #fff;
        border-color: #4F46E5;
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
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
        background: #fff;
        border-radius: 0 0 16px 16px;
    }
    
    .input-wrapper {
        display: flex;
        gap: 10px;
        background: #F1F5F9;
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 6px 8px 6px 20px;
        align-items: center;
    }
    
    .input-wrapper input {
        flex-grow: 1;
        border: none;
        background: transparent;
        padding: 6px 0;
        outline: none;
    }

    .quick-test-chip {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .quick-test-chip:hover {
        background-color: #EEF2FF !important;
        color: #4F46E5 !important;
        border-color: #C7D2FE !important;
    }
</style>
@endpush

@section('content')
<div class="ai-playground">
    <div class="playground-header">
        <div>
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-flask text-primary me-2"></i> AI Assistant Playground</h5>
            <small class="text-muted">Test how your AI responds to customers in natural Bangla with products and checkout links.</small>
        </div>
        <div>
            <span class="badge bg-soft-success text-success border border-success-subtle px-3 py-2">
                <i class="fa-solid fa-circle-check me-1"></i> Bangla AI Online
            </span>
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="location.reload()"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
        </div>
    </div>
    
    <div class="playground-chat" id="chatBox">
        
        <div class="text-center my-2">
            <span class="badge bg-light text-muted rounded-pill px-3 py-2 border"><i class="fa-solid fa-brain text-primary me-1"></i> Business Knowledge, Products & FAQ Loaded</span>
        </div>
        
        <div class="chat-bubble user">
            <div class="chat-avatar avatar-user">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <div class="chat-content">
                    আসসালামু আলাইকুম, আপনাদের প্রোডাক্টগুলো দেখতে চাই!
                </div>
                <div class="chat-debug">
                    <span>Simulating: Customer Chat</span>
                </div>
            </div>
        </div>
        
        <div class="chat-bubble ai">
            <div class="chat-avatar avatar-ai">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div>
                <div class="chat-content">
                    জি অবশ্যই! আসসালামু আলাইকুম।<br>আমাদের জনপ্রিয় সেরা প্রোডাক্টগুলোর তালিকা নিচে দেওয়া হলো:<br><br>
                    
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach(\App\Models\Product::where('status', true)->take(3)->get() as $p)
                        <div class="card border-0 shadow-sm p-2 bg-white rounded-4 overflow-hidden text-start d-inline-block align-top me-2 mb-2" style="width: 220px; border: 1px solid #CBD5E1 !important;">
                            <div class="position-relative mb-2">
                                <img src="{{ $p->image }}" class="w-100 rounded-3" style="height: 125px; object-fit: cover;">
                                @if($p->discount_price > 0)
                                    <span class="position-absolute top-0 end-0 bg-danger text-white badge rounded-pill m-2" style="font-size: 0.65rem;">অফার</span>
                                @endif
                            </div>
                            <div class="px-1">
                                <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $p->name }}" style="font-size: 0.85rem;">{{ $p->name }}</h6>
                                <div class="mb-2" style="font-size: 0.875rem;"><span class="fw-bold text-success">মূল্য: ৳{{ number_format($p->discount_price ?: $p->price, 0) }}</span></div>
                                <a href="{{ route('checkout', ['productId' => $p->id]) }}" target="_blank" class="btn btn-sm btn-success w-100 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; padding: 7px 10px;">
                                    <i class="fa-solid fa-cart-shopping me-1"></i> অর্ডার করুন (Order Now)
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    পছন্দের পণ্যের নিচে 'অর্ডার করুন' বাটনে ক্লিক করে সরাসরি অর্ডার করতে পারেন!
                </div>
                <div class="chat-debug">
                    <span><i class="fa-solid fa-bolt text-warning me-1"></i> Intent: Products</span>
                    <span><i class="fa-solid fa-bullseye text-success me-1"></i> Confidence: 98%</span>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Quick Prompt Chips -->
    <div class="px-4 py-2 bg-light border-top d-flex gap-2 overflow-auto">
        <span class="small text-muted fw-bold align-self-center flex-shrink-0">Quick Test:</span>
        <span class="badge bg-white text-dark border rounded-pill px-3 py-2 quick-test-chip" onclick="sendQuickPrompt('প্রোডাক্ট দেখান')">প্রোডাক্ট দেখান</span>
        <span class="badge bg-white text-dark border rounded-pill px-3 py-2 quick-test-chip" onclick="sendQuickPrompt('দাম কত?')">দাম কত?</span>
        <span class="badge bg-white text-dark border rounded-pill px-3 py-2 quick-test-chip" onclick="sendQuickPrompt('অর্ডার করার নিয়ম কি?')">অর্ডার নিয়ম</span>
        <span class="badge bg-white text-dark border rounded-pill px-3 py-2 quick-test-chip" onclick="sendQuickPrompt('hi')">Greetings</span>
    </div>
    
    <div class="playground-input">
        <div class="input-wrapper">
            <input type="text" id="chatInput" placeholder="Type a test message in Bangla or English..." autocomplete="off">
            <button id="sendBtn" class="btn btn-primary rounded-circle" style="width: 38px; height: 38px;"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const defaultImg = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80';
    const productsMap = @json(\App\Models\Product::where('status', true)->get()->keyBy('id'));
    const productsArray = Object.values(productsMap);

    function parseProductCardsJs(rawText) {
        if (!rawText) return '';
        let text = rawText;

        text = text.replace(/\[PRODUCT_CARD:(\d+)\]/g, function(match, idStr) {
            const pId = parseInt(idStr, 10);
            let prod = productsMap[pId];
            
            if (!prod && productsArray.length > 0) {
                prod = productsArray[(pId - 1) % productsArray.length];
            }

            if (!prod) return '';

            const imgUrl = prod.image ? prod.image : defaultImg;
            const priceDisplay = '৳' + Number(prod.discount_price || prod.price).toLocaleString();
            const originalPrice = (prod.discount_price && prod.discount_price > 0) ? `<span class="text-muted text-decoration-line-through ms-1" style="font-size: 0.75rem;">৳${Number(prod.price).toLocaleString()}</span>` : '';
            const badge = (prod.discount_price && prod.discount_price > 0) ? '<span class="position-absolute top-0 end-0 bg-danger text-white badge rounded-pill m-2 shadow-sm" style="font-size: 0.65rem;">অফার</span>' : '';

            return `<div class="product-card-item card border-0 shadow-sm my-2 p-2 bg-white rounded-4 overflow-hidden text-start d-inline-block align-top me-2 mb-2" style="width: 220px; border: 1px solid #CBD5E1 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;">
                <div class="position-relative mb-2">
                    <img src="${imgUrl}" onerror="this.src='${defaultImg}'" class="w-100 rounded-3" style="height: 125px; object-fit: cover;">
                    ${badge}
                </div>
                <div class="px-1">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="${prod.name}" style="font-size: 0.85rem;">${prod.name}</h6>
                    <div class="mb-2" style="font-size: 0.875rem;"><span class="fw-bold text-success">${priceDisplay}</span>${originalPrice}</div>
                    <a href="/checkout/${prod.id}" target="_blank" class="btn btn-sm btn-success w-100 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; padding: 7px 10px;">
                        <i class="fa-solid fa-cart-shopping me-1"></i> অর্ডার করুন (Order Now)
                    </a>
                </div>
            </div>`;
        });

        return text.replace(/\n/g, '<br>');
    }

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
                    <div class="chat-debug"><span>Simulating: Customer Chat</span></div>
                </div>
            </div>`;
            chatBox.insertAdjacentHTML('beforeend', html);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        async function appendAIMessage(userText) {
            const typingId = 'typing-' + Date.now();
            const typingHtml = `
            <div id="${typingId}" class="chat-bubble ai" style="animation: fadeIn 0.3s ease;">
                <div class="chat-avatar avatar-ai"><i class="fa-solid fa-robot"></i></div>
                <div>
                    <div class="chat-content"><i class="fa-solid fa-ellipsis fa-fade"></i> AI is responding...</div>
                </div>
            </div>`;
            chatBox.insertAdjacentHTML('beforeend', typingHtml);
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await fetch('/api/internal/ai/chat/simulate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userText })
                });
                
                const data = await response.json();
                document.getElementById(typingId).remove();
                
                const parsedReply = parseProductCardsJs(data.reply);

                const html = `
                <div class="chat-bubble ai" style="animation: fadeIn 0.3s ease;">
                    <div class="chat-avatar avatar-ai"><i class="fa-solid fa-robot"></i></div>
                    <div>
                        <div class="chat-content">${parsedReply}</div>
                        <div class="chat-debug">
                            <span><i class="fa-solid fa-bolt text-warning"></i> Intent: ${data.intent}</span>
                            <span><i class="fa-solid fa-bullseye text-success"></i> Confidence: ${data.confidence}</span>
                        </div>
                    </div>
                </div>`;
                chatBox.insertAdjacentHTML('beforeend', html);
                chatBox.scrollTop = chatBox.scrollHeight;
                
            } catch (error) {
                if (document.getElementById(typingId)) document.getElementById(typingId).remove();
                console.error("API Error:", error);
            }
        }

        window.sendQuickPrompt = function(msg) {
            appendUserMessage(msg);
            appendAIMessage(msg);
        };

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

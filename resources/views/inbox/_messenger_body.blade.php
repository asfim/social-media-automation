@php
    $allProducts = \App\Models\Product::where('status', true)->get()->keyBy('id');
    $defaultImage = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80';

    if (!function_exists('renderFormattedMessage')) {
        function renderFormattedMessage($messageText, $allProducts, $convId, $defaultImage) {
            $escaped = e($messageText);

            $output = preg_replace_callback('/\[PRODUCT_CARD:(\d+)\]/', function($matches) use ($allProducts, $convId, $defaultImage) {
                $pId = (int)$matches[1];
                $prod = $allProducts->get($pId);
                
                if (!$prod) {
                    $prod = $allProducts->values()->get(($pId - 1) % max(1, $allProducts->count()));
                }

                if (!$prod) return '';

                $imgUrl = !empty($prod->image) ? e($prod->image) : $defaultImage;
                $priceDisplay = '৳' . number_format($prod->discount_price ?: $prod->price, 0);
                $originalPrice = ($prod->discount_price && $prod->discount_price > 0) ? '<span class="text-muted text-decoration-line-through ms-1" style="font-size: 0.75rem;">৳' . number_format($prod->price, 0) . '</span>' : '';
                $badge = ($prod->discount_price && $prod->discount_price > 0) ? '<span class="position-absolute top-0 end-0 bg-danger text-white badge rounded-pill m-2 shadow-sm" style="font-size: 0.65rem;">অফার</span>' : '';

                return '<div class="product-card-item card border-0 shadow-sm my-2 p-2 bg-white rounded-4 overflow-hidden text-start d-inline-block align-top me-2 mb-2" style="width: 220px; border: 1px solid #CBD5E1 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;">'
                    . '<div class="position-relative mb-2">'
                    . '<img src="' . $imgUrl . '" onerror="this.src=\'' . $defaultImage . '\'" class="w-100 rounded-3" style="height: 125px; object-fit: cover;">'
                    . $badge
                    . '</div>'
                    . '<div class="px-1">'
                    . '<h6 class="fw-bold text-dark mb-1 text-truncate" title="' . e($prod->name) . '" style="font-size: 0.85rem;">' . e($prod->name) . '</h6>'
                    . '<div class="mb-2" style="font-size: 0.875rem;"><span class="fw-bold text-success">' . $priceDisplay . '</span>' . $originalPrice . '</div>'
                    . '<a href="' . route('checkout', ['productId' => $prod->id]) . '?conversation_id=' . $convId . '" target="_blank" class="btn btn-sm btn-success w-100 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; padding: 7px 10px;">'
                    . '<i class="fa-solid fa-cart-shopping me-1"></i> অর্ডার করুন (Order Now)</a>'
                    . '</div></div>';
            }, $escaped);

            return nl2br($output);
        }
    }
@endphp

<div class="inbox-container">

    <!-- Sidebar: Conversations List -->
    <div class="inbox-sidebar {{ $activeConversation ? 'hide-mobile' : '' }}">
        <div class="inbox-search">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0 pe-1 text-muted"><i class="fa-solid fa-search"></i></span>
                <input type="text" id="convSearch" class="form-control bg-transparent border-0 ps-1" placeholder="Search conversations...">
            </div>
        </div>

        <div class="inbox-list" id="convList">
            @forelse($conversations as $conv)
                @php $last = $conv->lastMessage; @endphp
                <a href="{{ route('inbox.messenger', ['id' => $conv->id]) }}" class="text-decoration-none conv-link" data-name="{{ strtolower($conv->customer_name . ' ' . ($last->message_text ?? '')) }}">
                    <div class="conversation-item {{ $activeConversation && $activeConversation->id === $conv->id ? 'active' : '' }}">
                        <div class="conversation-avatar-container">
                            <img src="{{ $conv->customer_avatar ?: 'https://ui-avatars.com/api/?background=random&name=' . urlencode($conv->customer_name) }}" alt="{{ $conv->customer_name }}">
                            <div class="platform-icon-badge">
                                <i class="fa-brands fa-{{ $conv->platform === 'instagram' ? 'instagram text-danger' : ($conv->platform === 'whatsapp' ? 'whatsapp text-success' : 'facebook text-primary') }}"></i>
                            </div>
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-name">
                                <span class="text-truncate">{{ $conv->customer_name }}</span>
                                <span class="conversation-time ms-1">{{ ($conv->last_message_at ?? $conv->updated_at)->diffForHumans(null, true, true) }}</span>
                            </div>
                            <div class="conversation-preview">{{ $last->message_text ?? 'No messages yet' }}</div>
                            <div class="mt-1 d-flex align-items-center gap-1">
                                <span class="badge {{ $conv->ai_active ? 'bg-soft-success' : 'bg-soft-primary' }}" style="font-size: 0.65rem;">
                                    <i class="fa-solid fa-{{ $conv->ai_active ? 'robot' : 'user' }} me-1"></i>{{ $conv->ai_active ? 'AI Active' : 'Human' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted p-4 my-auto">
                    <i class="fa-regular fa-comments fs-1 d-block mb-2 text-primary opacity-50"></i>
                    <span class="fw-medium">No conversations yet.</span><br>
                    <small class="text-muted">They will appear here when customers message your page.</small>
                </div>
            @endforelse
        </div>
    </div>

    @if($activeConversation)
    <!-- Main Chat Area -->
    <div class="inbox-main show-mobile">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-2 min-w-0">
                <a href="{{ route('inbox.messenger') }}" class="btn btn-sm btn-light rounded-circle d-md-none me-1" title="Back to conversations">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h5 class="mb-0 fw-bold text-truncate me-2">{{ $activeConversation->customer_name }}</h5>
                <span id="aiBadge" class="badge {{ $activeConversation->ai_active ? 'bg-soft-success' : 'bg-soft-primary' }} flex-shrink-0">
                    @if($activeConversation->ai_active)
                        <i class="fa-solid fa-robot me-1"></i> AI is handling this
                    @else
                        <i class="fa-solid fa-user me-1"></i> Human handling
                    @endif
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <button id="simulateBtn" class="btn btn-sm btn-emerald rounded-pill px-3 text-white shadow-sm" style="background: #10B981;" title="Simulate customer message to test Bangla reply & products">
                    <i class="fa-solid fa-vial me-1"></i> Test Customer Chat
                </button>
                <button id="toggleAiBtn" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="fa-solid fa-user me-1"></i> <span>{{ $activeConversation->ai_active ? 'Take Over' : 'Resume AI' }}</span>
                </button>
                <button id="toggleInfoBtn" class="btn btn-sm btn-light rounded-circle shadow-sm border" title="Toggle Lead Details">
                    <i class="fa-solid fa-circle-info text-secondary"></i>
                </button>
            </div>
        </div>

        <!-- Simulation Test Bar -->
        <div id="simBar" class="bg-light border-bottom p-2 px-3 d-none">
            <div class="d-flex align-items-center gap-2">
                <span class="small fw-bold text-muted flex-shrink-0"><i class="fa-solid fa-user me-1"></i> Test Customer:</span>
                <button class="btn btn-sm btn-outline-secondary rounded-pill sim-quick-btn" data-msg="আমি প্রোডাক্ট দেখতে চাই">প্রোডাক্ট দেখান</button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill sim-quick-btn" data-msg="দাম কত?">দাম কত?</button>
                <button class="btn btn-sm btn-outline-secondary rounded-pill sim-quick-btn" data-msg="অর্ডার করবো কিভাবে?">অর্ডার নিয়ম</button>
                <div class="input-group input-group-sm flex-grow-1">
                    <input type="text" id="simCustomMsg" class="form-control" placeholder="Type custom customer message in Bangla/Banglish...">
                    <button class="btn btn-success" type="button" id="simSendBtn"><i class="fa-solid fa-paper-plane me-1"></i> Send</button>
                </div>
            </div>
        </div>

        <div class="chat-history" id="chatHistory">
            @forelse($messages as $msg)
                @if($msg->sender_type === 'customer')
                    <div class="message received">
                        {!! nl2br(e($msg->message_text)) !!}
                        <div class="message-time">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @elseif($msg->sender_type === 'ai')
                    <div class="message ai-sent">
                        <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Assistant</small></div>
                        {!! renderFormattedMessage($msg->message_text, $allProducts, $activeConversation->id, $defaultImage) !!}
                        <div class="message-time text-end">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @else
                    <div class="message sent">
                        {!! nl2br(e($msg->message_text)) !!}
                        <div class="message-time text-end">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @endif
            @empty
                <div class="text-center text-muted my-auto">
                    <i class="fa-regular fa-comment-dots fs-2 d-block mb-2 text-primary opacity-50"></i>
                    No messages in this conversation yet.
                </div>
            @endforelse
        </div>

        <div class="chat-input-container">
            <form class="chat-input" id="sendForm">
                <input type="text" id="msgInput" placeholder="Type your admin message..." autocomplete="off" maxlength="2000">
                <button type="submit" class="btn btn-primary shadow-sm" id="sendBtn" title="Send Message">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar: Customer & Lead Info -->
    <div class="inbox-info" id="inboxInfo">
        <div class="customer-profile">
            <img src="{{ $activeConversation->customer_avatar ?: 'https://ui-avatars.com/api/?background=random&name=' . urlencode($activeConversation->customer_name) }}" alt="{{ $activeConversation->customer_name }}">
            <h5 class="text-truncate" title="{{ $activeConversation->customer_name }}">{{ $activeConversation->customer_name }}</h5>
            <div class="text-muted small text-capitalize"><i class="fa-brands fa-{{ $activeConversation->platform }} me-1"></i> {{ $activeConversation->platform }} user</div>
        </div>

        <div class="info-card">
            <div class="info-card-header"><i class="fa-solid fa-user-tag text-primary"></i> Lead Overview</div>
            
            <div class="info-group">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="badge bg-{{ ($lead->lead_score ?? $activeConversation->lead_score) >= 90 ? 'danger' : 'secondary' }} rounded-pill px-3">{{ $lead->lead_status ?? 'No lead yet' }}</span>
                </div>
            </div>

            <div class="info-group">
                <div class="info-label">Lead Score</div>
                @php $score = $lead->lead_score ?? $activeConversation->lead_score ?? 0; @endphp
                <div class="info-value d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($score, 100) }}%;"></div>
                    </div>
                    <span class="fw-bold fs-6">{{ $score }}</span>
                </div>
            </div>

            <div class="info-group">
                <div class="info-label">Interested Service</div>
                <div class="info-value text-primary fw-semibold">{{ $lead->interested_service ?? '-' }}</div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header"><i class="fa-solid fa-address-card text-primary"></i> Contact Details</div>

            <div class="info-group">
                <div class="info-label">Phone</div>
                <div class="info-value">
                    @if(!empty($lead->phone))
                        <a href="tel:{{ $lead->phone }}" class="text-decoration-none text-dark"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $lead->phone }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="info-group">
                <div class="info-label">Email</div>
                <div class="info-value">
                    @if(!empty($lead->email))
                        <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-dark"><i class="fa-solid fa-envelope me-1 text-muted"></i>{{ $lead->email }}</a>
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="inbox-main d-flex align-items-center justify-content-center text-muted">
        <div class="text-center p-4">
            <i class="fa-regular fa-comments fs-1 mb-3 d-block text-primary opacity-50"></i>
            <h5 class="fw-bold text-dark">No conversation selected</h5>
            <p class="text-muted small mb-0">Select a conversation from the left panel to start chatting.</p>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    const csrf = '{{ csrf_token() }}';
    const history = document.getElementById('chatHistory');
    if (history) history.scrollTop = history.scrollHeight;

    const defaultImg = '{{ $defaultImage }}';
    const productsMap = @json($allProducts);
    const productsArray = Object.values(productsMap);

    function parseProductCardsJs(rawText, convId) {
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
                    <a href="/checkout/${prod.id}?conversation_id=${convId || ''}" target="_blank" class="btn btn-sm btn-success w-100 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; padding: 7px 10px;">
                        <i class="fa-solid fa-cart-shopping me-1"></i> অর্ডার করুন (Order Now)
                    </a>
                </div>
            </div>`;
        });

        return text.replace(/\n/g, '<br>');
    }

    // Search filter
    const searchInput = document.getElementById('convSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.conv-link').forEach(el => {
                el.style.display = el.dataset.name.includes(q) ? '' : 'none';
            });
        });
    }

    // Toggle Details Sidebar
    const toggleInfoBtn = document.getElementById('toggleInfoBtn');
    const inboxInfo = document.getElementById('inboxInfo');
    if (toggleInfoBtn && inboxInfo) {
        toggleInfoBtn.addEventListener('click', () => {
            inboxInfo.classList.toggle('show');
        });
    }

    // Toggle Simulation Bar
    const simulateBtn = document.getElementById('simulateBtn');
    const simBar = document.getElementById('simBar');
    if (simulateBtn && simBar) {
        simulateBtn.addEventListener('click', () => {
            simBar.classList.toggle('d-none');
        });
    }

    @if($activeConversation)
    const convId = {{ $activeConversation->id }};
    const headers = {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf};

    function escapeHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function setHumanMode(human) {
        const badge = document.getElementById('aiBadge');
        if (badge) {
            badge.className = 'badge ' + (human ? 'bg-soft-primary' : 'bg-soft-success') + ' flex-shrink-0';
            badge.innerHTML = human ? '<i class="fa-solid fa-user me-1"></i> Human handling' : '<i class="fa-solid fa-robot me-1"></i> AI is handling this';
        }
        const toggleBtnSpan = document.querySelector('#toggleAiBtn span');
        if (toggleBtnSpan) {
            toggleBtnSpan.textContent = human ? 'Resume AI' : 'Take Over';
        }
    }

    const toggleAiBtn = document.getElementById('toggleAiBtn');
    if (toggleAiBtn) {
        toggleAiBtn.addEventListener('click', async () => {
            try {
                const res = await fetch(`/inbox/conversations/${convId}/toggle-ai`, {method: 'POST', headers});
                const data = await res.json();
                if (data.success) setHumanMode(!data.ai_active);
            } catch (err) {
                console.error(err);
            }
        });
    }

    // Send Admin Message
    const sendForm = document.getElementById('sendForm');
    if (sendForm) {
        sendForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('msgInput');
            const text = input.value.trim();
            if (!text) return;
            const btn = document.getElementById('sendBtn');
            btn.disabled = true;
            try {
                const res = await fetch(`/inbox/conversations/${convId}/send`, {method: 'POST', headers, body: JSON.stringify({message: text})});
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to send');
                history.insertAdjacentHTML('beforeend', `<div class="message sent">${escapeHtml(text)}<div class="message-time text-end">${data.time}</div></div>`);
                history.scrollTop = history.scrollHeight;
                input.value = '';
                setHumanMode(true);
            } catch (err) {
                alert(err.message);
            } finally {
                btn.disabled = false;
            }
        });
    }

    // Send Simulated Customer Message
    async function sendSimulatedCustomerMessage(text) {
        if (!text) return;
        try {
            const res = await fetch(`/inbox/conversations/${convId}/simulate-customer`, {
                method: 'POST',
                headers,
                body: JSON.stringify({message: text})
            });
            const data = await res.json();
            if (!data.success) throw new Error('Simulation failed');

            // 1. Add Customer Message
            history.insertAdjacentHTML('beforeend', `
                <div class="message received">
                    ${escapeHtml(data.customer_message.text).replace(/\n/g, '<br>')}
                    <div class="message-time">${data.customer_message.time}</div>
                </div>
            `);

            // 2. Add AI Response if available
            if (data.ai_message) {
                const parsedContent = parseProductCardsJs(data.ai_message.text, convId);
                history.insertAdjacentHTML('beforeend', `
                    <div class="message ai-sent">
                        <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Assistant</small></div>
                        ${parsedContent}
                        <div class="message-time text-end">${data.ai_message.time}</div>
                    </div>
                `);
            }

            history.scrollTop = history.scrollHeight;
        } catch (err) {
            alert(err.message);
        }
    }

    document.querySelectorAll('.sim-quick-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            sendSimulatedCustomerMessage(this.dataset.msg);
        });
    });

    const simSendBtn = document.getElementById('simSendBtn');
    if (simSendBtn) {
        simSendBtn.addEventListener('click', () => {
            const input = document.getElementById('simCustomMsg');
            if (input.value.trim()) {
                sendSimulatedCustomerMessage(input.value.trim());
                input.value = '';
            }
        });
    }
    @endif
</script>
@endpush

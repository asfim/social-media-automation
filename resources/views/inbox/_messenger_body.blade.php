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
                <button id="toggleAiBtn" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="fa-solid fa-user me-1"></i> <span>{{ $activeConversation->ai_active ? 'Take Over' : 'Resume AI' }}</span>
                </button>
                <button id="toggleInfoBtn" class="btn btn-sm btn-light rounded-circle shadow-sm border" title="Toggle Lead Details">
                    <i class="fa-solid fa-circle-info text-secondary"></i>
                </button>
            </div>
        </div>

        <div class="chat-history" id="chatHistory">
            @forelse($messages as $msg)
                @if($msg->sender_type === 'customer')
                    <div class="message received">
                        {{ $msg->message_text }}
                        <div class="message-time">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @elseif($msg->sender_type === 'ai')
                    <div class="message ai-sent">
                        <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Assistant</small></div>
                        {{ $msg->message_text }}
                        <div class="message-time text-end">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @else
                    <div class="message sent">
                        {{ $msg->message_text }}
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
                <input type="text" id="msgInput" placeholder="Type your message to take over..." autocomplete="off" maxlength="2000">
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

    // Toggle Details Sidebar (Right Panel)
    const toggleInfoBtn = document.getElementById('toggleInfoBtn');
    const inboxInfo = document.getElementById('inboxInfo');
    if (toggleInfoBtn && inboxInfo) {
        toggleInfoBtn.addEventListener('click', () => {
            inboxInfo.classList.toggle('show');
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
                history.scrollTop = history.scrollTop = history.scrollHeight;
                input.value = '';
                setHumanMode(true);
                if (data.warning) alert('Saved, but not delivered to customer: ' + data.warning);
            } catch (err) {
                alert(err.message);
            } finally {
                btn.disabled = false;
            }
        });
    }
    @endif
</script>
@endpush

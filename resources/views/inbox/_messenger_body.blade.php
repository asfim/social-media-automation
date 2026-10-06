<div class="inbox-container">

    <!-- Sidebar: Conversations -->
    <div class="inbox-sidebar">
        <div class="inbox-search">
            <input type="text" id="convSearch" class="form-control rounded-pill border-0 shadow-sm" placeholder="Search conversations...">
        </div>

        <div class="inbox-list" id="convList">
            @forelse($conversations as $conv)
                @php $last = $conv->lastMessage; @endphp
                <a href="{{ route('inbox.messenger', ['id' => $conv->id]) }}" class="text-decoration-none conv-link" data-name="{{ strtolower($conv->customer_name) }}">
                    <div class="conversation-item {{ $activeConversation && $activeConversation->id === $conv->id ? 'active' : '' }}">
                        <img src="{{ $conv->customer_avatar ?: 'https://ui-avatars.com/api/?background=random&name=' . urlencode($conv->customer_name) }}" alt="{{ $conv->customer_name }}">
                        <div class="conversation-info">
                            <div class="conversation-name">
                                {{ $conv->customer_name }}
                                <span class="conversation-time">{{ ($conv->last_message_at ?? $conv->updated_at)->diffForHumans(null, true, true) }}</span>
                            </div>
                            <div class="conversation-preview">{{ $last->message_text ?? 'No messages yet' }}</div>
                            <div class="mt-1">
                                <span class="badge {{ $conv->ai_active ? 'bg-soft-success' : 'bg-soft-primary' }}" style="font-size: 0.65rem;">{{ $conv->ai_active ? 'AI Active' : 'Human' }}</span>
                                <i class="fa-brands fa-{{ $conv->platform === 'instagram' ? 'instagram text-danger' : ($conv->platform === 'whatsapp' ? 'whatsapp text-success' : 'facebook text-primary') }} ms-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted p-4">
                    <i class="fa-regular fa-comments fs-2 d-block mb-2"></i>
                    No conversations yet.<br>
                    <small>They appear here once Meta webhooks start delivering messages.</small>
                </div>
            @endforelse
        </div>
    </div>

    @if($activeConversation)
    <!-- Main Chat Area -->
    <div class="inbox-main">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-bold">{{ $activeConversation->customer_name }}</h5>
                <span id="aiBadge" class="badge {{ $activeConversation->ai_active ? 'bg-soft-success' : 'bg-soft-primary' }}">
                    @if($activeConversation->ai_active)
                        <i class="fa-solid fa-robot me-1"></i> AI is handling this
                    @else
                        <i class="fa-solid fa-user me-1"></i> Human handling
                    @endif
                </span>
            </div>
            <button id="toggleAiBtn" class="btn btn-sm btn-outline-danger rounded-pill">
                <i class="fa-solid fa-user me-1"></i> <span>{{ $activeConversation->ai_active ? 'Take Over' : 'Resume AI' }}</span>
            </button>
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
                        <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-wand-magic-sparkles"></i> AI Assistant</small></div>
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
                <div class="text-center text-muted my-auto">No messages in this conversation yet.</div>
            @endforelse
        </div>

        <form class="chat-input" id="sendForm">
            <input type="text" id="msgInput" placeholder="Type your message to take over..." autocomplete="off" maxlength="2000">
            <button type="submit" class="btn btn-primary rounded-circle" id="sendBtn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <!-- Sidebar: Customer Info -->
    <div class="inbox-info">
        <div class="customer-profile">
            <img src="{{ $activeConversation->customer_avatar ?: 'https://ui-avatars.com/api/?background=random&name=' . urlencode($activeConversation->customer_name) }}" alt="{{ $activeConversation->customer_name }}">
            <h5 class="mb-1">{{ $activeConversation->customer_name }}</h5>
            <div class="text-muted small text-capitalize">{{ $activeConversation->platform }} user</div>
        </div>

        <div class="info-group">
            <div class="info-label">Lead Status</div>
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
                <span class="fw-bold">{{ $score }}</span>
            </div>
        </div>

        <div class="info-group">
            <div class="info-label">Interested Service</div>
            <div class="info-value">{{ $lead->interested_service ?? '-' }}</div>
        </div>
        <div class="info-group">
            <div class="info-label">Phone</div>
            <div class="info-value">{{ $lead->phone ?? '-' }}</div>
        </div>
        <div class="info-group">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $lead->email ?? '-' }}</div>
        </div>
    </div>
    @else
    <div class="inbox-main d-flex align-items-center justify-content-center text-muted">
        <div class="text-center">
            <i class="fa-regular fa-comment-dots fs-1 mb-3 d-block"></i>
            Select a conversation to start chatting.
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
    document.getElementById('convSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.conv-link').forEach(el => {
            el.style.display = el.dataset.name.includes(q) ? '' : 'none';
        });
    });

    @if($activeConversation)
    const convId = {{ $activeConversation->id }};
    const headers = {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf};

    function escapeHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function setHumanMode(human) {
        const badge = document.getElementById('aiBadge');
        badge.className = 'badge ' + (human ? 'bg-soft-primary' : 'bg-soft-success');
        badge.innerHTML = human ? '<i class="fa-solid fa-user me-1"></i> Human handling' : '<i class="fa-solid fa-robot me-1"></i> AI is handling this';
        document.querySelector('#toggleAiBtn span').textContent = human ? 'Resume AI' : 'Take Over';
    }

    document.getElementById('toggleAiBtn').addEventListener('click', async () => {
        const res = await fetch(`/inbox/conversations/${convId}/toggle-ai`, {method: 'POST', headers});
        const data = await res.json();
        if (data.success) setHumanMode(!data.ai_active);
    });

    document.getElementById('sendForm').addEventListener('submit', async (e) => {
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
            if (data.warning) alert('Saved, but not delivered to customer: ' + data.warning);
        } catch (err) {
            alert(err.message);
        } finally {
            btn.disabled = false;
        }
    });
    @endif
</script>
@endpush

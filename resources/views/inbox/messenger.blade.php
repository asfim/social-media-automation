@extends('layouts.app')

@section('title', 'Messenger Inbox')

@push('styles')
<style>
    .inbox-container {
        height: calc(100vh - 140px);
        background: #fff;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        display: flex;
        overflow: hidden;
    }
    
    /* Conversations List (Left) */
    .inbox-sidebar {
        width: 320px;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        background: #FAFAFA;
    }
    
    .inbox-search {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .inbox-list {
        flex-grow: 1;
        overflow-y: auto;
    }
    
    .conversation-item {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        gap: 12px;
    }
    
    .conversation-item:hover {
        background-color: #F1F5F9;
    }
    
    .conversation-item.active {
        background-color: #EEF2FF;
        border-left: 3px solid var(--primary);
    }
    
    .conversation-item img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
    }
    
    .conversation-info {
        flex-grow: 1;
        overflow: hidden;
    }
    
    .conversation-name {
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    
    .conversation-time {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 400;
    }
    
    .conversation-preview {
        font-size: 0.85rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Main Chat Area (Center) */
    .inbox-main {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .chat-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    
    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .message {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.95rem;
    }
    
    .message.received {
        align-self: flex-start;
        background: #fff;
        border: 1px solid var(--border-color);
        border-bottom-left-radius: 2px;
    }
    
    .message.sent {
        align-self: flex-end;
        background: var(--primary);
        color: #fff;
        border-bottom-right-radius: 2px;
    }
    
    .message.ai-sent {
        align-self: flex-end;
        background: #EEF2FF;
        color: var(--primary-dark);
        border: 1px solid #C7D2FE;
        border-bottom-right-radius: 2px;
    }
    
    .message-time {
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.8;
    }
    
    .chat-input {
        padding: 15px 20px;
        border-top: 1px solid var(--border-color);
        background: #fff;
        display: flex;
        gap: 10px;
    }
    
    .chat-input input {
        flex-grow: 1;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 10px 20px;
    }
    
    .chat-input input:focus {
        outline: none;
        border-color: var(--primary);
    }
    
    /* Customer Info (Right) */
    .inbox-info {
        width: 300px;
        border-left: 1px solid var(--border-color);
        background: #FAFAFA;
        padding: 20px;
        overflow-y: auto;
    }
    
    .customer-profile {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .customer-profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
    }
    
    .info-group {
        margin-bottom: 15px;
    }
    
    .info-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .info-value {
        font-size: 0.95rem;
        color: var(--text-main);
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="inbox-container">
    
    <!-- Sidebar: Conversations -->
    <div class="inbox-sidebar">
        <div class="inbox-search">
            <input type="text" class="form-control rounded-pill border-0 shadow-sm" placeholder="Search conversations...">
            <div class="d-flex gap-2 mt-3">
                <span class="badge bg-primary rounded-pill px-3 py-2">All</span>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2">Unread</span>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2">Hot Leads</span>
            </div>
        </div>
        
        <div class="inbox-list">
            <!-- Item 1 -->
            <div class="conversation-item active">
                <img src="https://ui-avatars.com/api/?name=Rahim+Ahmed&background=random" alt="Rahim">
                <div class="conversation-info">
                    <div class="conversation-name">
                        Rahim Ahmed <span class="conversation-time">2m ago</span>
                    </div>
                    <div class="conversation-preview fw-bold text-dark">
                        Website করতে কত টাকা লাগবে?
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-soft-success" style="font-size: 0.65rem;">AI Active</span>
                        <i class="fa-brands fa-facebook text-primary ms-1"></i>
                    </div>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="conversation-item">
                <img src="https://ui-avatars.com/api/?name=Karim+Hasan&background=random" alt="Karim">
                <div class="conversation-info">
                    <div class="conversation-name">
                        Karim Hasan <span class="conversation-time">1h ago</span>
                    </div>
                    <div class="conversation-preview">
                        Thank you!
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-soft-primary" style="font-size: 0.65rem;">Human</span>
                        <i class="fa-brands fa-instagram text-danger ms-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Chat Area -->
    <div class="inbox-main">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-bold">Rahim Ahmed</h5>
                <span class="badge bg-soft-success"><i class="fa-solid fa-robot me-1"></i> AI is handling this</span>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-danger rounded-pill">
                    <i class="fa-solid fa-user me-1"></i> Take Over
                </button>
            </div>
        </div>
        
        <div class="chat-history">
            <div class="text-center my-2">
                <span class="badge bg-light text-muted rounded-pill px-3">Today, 10:42 AM</span>
            </div>
            
            <div class="message received">
                Hi, I want to build an eCommerce website for my business.
                <div class="message-time">10:42 AM</div>
            </div>
            
            <div class="message ai-sent">
                <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-sparkles"></i> AI Assistant</small></div>
                Hello Rahim! We would love to help you build your eCommerce website. Could you tell me a little bit about what kind of products you sell?
                <div class="message-time text-end">10:43 AM</div>
            </div>
            
            <div class="message received">
                Website করতে কত টাকা লাগবে?
                <div class="message-time">10:45 AM</div>
            </div>
            
            <div class="message ai-sent">
                <div class="mb-1"><small class="fw-bold"><i class="fa-solid fa-sparkles"></i> AI Assistant</small></div>
                আপনার প্রয়োজন অনুযায়ী আমাদের eCommerce website-এর pricing 20,000 BDT থেকে শুরু হয়। আপনি কি আমাদের সম্পূর্ণ প্যাকেজ এবং ফিচারগুলো দেখতে চান?
                <div class="message-time text-end">10:45 AM</div>
            </div>
        </div>
        
        <div class="chat-input">
            <button class="btn btn-light rounded-circle"><i class="fa-solid fa-paperclip"></i></button>
            <input type="text" placeholder="Type your message to take over...">
            <button class="btn btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Suggest</button>
            <button class="btn btn-primary rounded-circle"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
    
    <!-- Sidebar: Customer Info -->
    <div class="inbox-info">
        <div class="customer-profile">
            <img src="https://ui-avatars.com/api/?name=Rahim+Ahmed&background=random" alt="Rahim">
            <h5 class="mb-1">Rahim Ahmed</h5>
            <div class="text-muted small"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook User</div>
        </div>
        
        <div class="info-group">
            <div class="info-label">Lead Status</div>
            <div class="info-value"><span class="badge bg-danger rounded-pill px-3">🔥 Hot Lead</span></div>
        </div>
        
        <div class="info-group">
            <div class="info-label">Lead Score</div>
            <div class="info-value">
                <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 95%;"></div>
                    </div>
                    <span class="fw-bold">95</span>
                </div>
            </div>
        </div>
        
        <div class="info-group">
            <div class="info-label">Interested Service</div>
            <div class="info-value">eCommerce Website</div>
        </div>
        
        <div class="info-group">
            <div class="info-label">Phone</div>
            <div class="info-value">+880 1712-XXXXXX <button class="btn btn-sm btn-link text-muted p-0 ms-2"><i class="fa-solid fa-pen"></i></button></div>
        </div>
        
        <div class="info-group">
            <div class="info-label">Email</div>
            <div class="info-value">rahim@example.com <button class="btn btn-sm btn-link text-muted p-0 ms-2"><i class="fa-solid fa-pen"></i></button></div>
        </div>
    </div>
    
</div>
@endsection

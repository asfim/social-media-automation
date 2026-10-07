@extends('layouts.app')

@section('title', 'Messenger Inbox')

@push('styles')
<style>
    .inbox-container {
        height: calc(100vh - 130px);
        min-height: 580px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border-color);
        display: flex;
        overflow: hidden;
        position: relative;
    }
    
    /* 1. Left Sidebar: Conversations */
    .inbox-sidebar {
        width: 310px;
        min-width: 310px;
        max-width: 310px;
        flex-shrink: 0;
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        background: #F8FAFC;
        transition: all 0.3s ease;
    }
    
    .inbox-search {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        background: #ffffff;
    }
    
    .inbox-list {
        flex-grow: 1;
        overflow-y: auto;
    }
    
    .conversation-item {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        gap: 12px;
        position: relative;
    }
    
    .conversation-item:hover {
        background-color: #F1F5F9;
    }
    
    .conversation-item.active {
        background-color: #EEF2FF;
        border-left: 4px solid var(--primary);
    }
    
    .conversation-avatar-container {
        position: relative;
        flex-shrink: 0;
    }

    .conversation-avatar-container img {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
    }

    .platform-icon-badge {
        position: absolute;
        bottom: 0;
        right: -2px;
        background: #ffffff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    
    .conversation-info {
        flex-grow: 1;
        min-width: 0;
    }
    
    .conversation-name {
        font-weight: 600;
        font-size: 0.92rem;
        color: var(--text-main);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3px;
    }
    
    .conversation-time {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 400;
        flex-shrink: 0;
    }
    
    .conversation-preview {
        font-size: 0.825rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* 2. Middle Main Chat Area */
    .inbox-main {
        flex: 1 1 0%;
        min-width: 0; /* CRITICAL FIX: prevents center pane from overflowing horizontally */
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }
    
    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        z-index: 10;
    }
    
    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    
    .message {
        max-width: 78%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.925rem;
        line-height: 1.5;
        word-break: break-word;
        overflow-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    
    .message.received {
        align-self: flex-start;
        background: #ffffff;
        color: var(--text-main);
        border: 1px solid var(--border-color);
        border-bottom-left-radius: 4px;
    }
    
    .message.sent {
        align-self: flex-end;
        background: var(--primary);
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }
    
    .message.ai-sent {
        align-self: flex-end;
        background: #EEF2FF;
        color: #312E81;
        border: 1px solid #C7D2FE;
        border-bottom-right-radius: 4px;
    }
    
    .message-time {
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.75;
    }
    
    .chat-input-container {
        padding: 14px 20px;
        border-top: 1px solid var(--border-color);
        background: #ffffff;
    }
    
    .chat-input {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #F1F5F9;
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 6px 8px 6px 20px;
        transition: all 0.2s ease;
    }
    
    .chat-input:focus-within {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .chat-input input {
        flex-grow: 1;
        border: none;
        background: transparent;
        font-size: 0.925rem;
        padding: 6px 0;
        outline: none;
    }
    
    .chat-input button {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.15s ease;
    }
    
    /* 3. Right Sidebar: Customer & Lead Info */
    .inbox-info {
        width: 290px;
        min-width: 290px;
        max-width: 290px;
        flex-shrink: 0;
        border-left: 1px solid var(--border-color);
        background: #FAFAFA;
        padding: 20px;
        overflow-y: auto;
        overflow-x: hidden; /* Fixes internal horizontal overflow */
        word-break: break-word;
        transition: all 0.3s ease;
    }
    
    .customer-profile {
        text-align: center;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 16px;
    }
    
    .customer-profile img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        margin-bottom: 10px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    
    .customer-profile h5 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
        color: var(--text-main);
        word-break: break-word;
    }
    
    .info-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 14px 16px;
        border: 1px solid var(--border-color);
        margin-bottom: 14px;
    }

    .info-card-header {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .info-group {
        margin-bottom: 12px;
    }
    .info-group:last-child {
        margin-bottom: 0;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    
    .info-value {
        font-size: 0.875rem;
        color: var(--text-main);
        font-weight: 500;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* Custom Scrollbars */
    .inbox-list::-webkit-scrollbar,
    .chat-history::-webkit-scrollbar,
    .inbox-info::-webkit-scrollbar {
        width: 5px;
    }
    .inbox-list::-webkit-scrollbar-thumb,
    .chat-history::-webkit-scrollbar-thumb,
    .inbox-info::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 10px;
    }

    /* Responsive Behavior */
    @media (max-width: 1199.98px) {
        .inbox-info {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            box-shadow: -4px 0 20px rgba(0,0,0,0.12);
            transform: translateX(100%);
            display: none;
        }
        .inbox-info.show {
            transform: translateX(0);
            display: block;
        }
    }

    @media (max-width: 767.98px) {
        .inbox-sidebar {
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }
        .inbox-sidebar.hide-mobile {
            display: none;
        }
        .inbox-main {
            display: none;
        }
        .inbox-main.show-mobile {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
@include('inbox._messenger_body')
@endsection


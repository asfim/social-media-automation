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
@include('inbox._messenger_body')
@endsection

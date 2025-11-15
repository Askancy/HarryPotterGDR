@extends('front.layouts.app')

@section('title', 'Messaggi Privati')

@section('styles')
<style>
    .conversations-list {
        max-height: 600px;
        overflow-y: auto;
    }
    .conversation-item {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background 0.3s;
    }
    .conversation-item:hover {
        background: #f8f9fa;
    }
    .conversation-item.active {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
    }
    .conversation-item.unread {
        background: #fff3cd;
    }
    .messages-area {
        height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1rem;
        background: #f8f9fa;
    }
    .message-bubble {
        max-width: 70%;
        padding: 0.75rem 1rem;
        border-radius: 18px;
        margin-bottom: 0.5rem;
    }
    .message-sent {
        background: #007bff;
        color: white;
        margin-left: auto;
        text-align: right;
    }
    .message-received {
        background: white;
        border: 1px solid #ddd;
    }
    .user-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-envelope"></i> Messaggi Privati</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row">
        @livewire('private-messages')
    </div>
</div>
@endsection

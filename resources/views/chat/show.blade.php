@extends('layouts.app') 

@section('title', 'Chat with ' . $receiver->name)

@section('content')
<style>
    :root {
        --color-page-bg: #FFFBE8; 
        --color-chat-list-bg: #E8E0BB; 
        --color-brown: #6C2207; 
        --color-white: #FFFEF7; 
    }

    body {
        background-color: var(--color-page-bg);
        color: var(--color-brown);
        margin: 0;
        padding: 0;
    }

    .header-fixed {
        background-color: var(--color-white);
        width: 100%;
        position: sticky; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }
    
    .text-brown {
        color: var(--color-brown) !important;
    }
    
    .chat-list-container {
        background-color: var(--color-chat-list-bg);
        height: calc(100vh - 80px); 
        overflow-y: auto;
    }

    .list-group-item.active {
        background-color: var(--color-page-bg) !important; 
        border-right: 2px solid var(--color-brown);
    }

    .list-group-item {
        background-color: var(--color-chat-list-bg);
        border: none;
        border-bottom: 1px solid rgba(108, 34, 7, 0.2);
    }

    .message-area {
        background-color: var(--color-page-bg);
        max-height: calc(100vh - 160px); 
        overflow-y: auto; 
    }
    
    .chat-input-footer {
        background-color: var(--color-white) !important;
        position: fixed;
        bottom: 0;
        z-index: 999; 
        border-top: 1px solid rgba(0, 0, 0, 0.1); 
        left: 0;
        right: 0; 
        width: auto; 
    }

    @media (min-width: 768px) {
        .chat-input-footer {
            left: 33.333333% !important; 
            right: 0 !important; 
            width: auto !important; 
        }
    }
    
    .message-bubble-sender {
        background-color: #D8C8B4 !important;
        color: var(--color-brown) !important;
    }

    .message-bubble-receiver {
        background-color: #FFFFFF !important;
        color: var(--color-brown) !important;
    }

    .sender-timestamp {
        color: rgba(108, 34, 7, 0.7) !important;
    }
    
</style>

{{-- Header: Back Button and Title (Fixed/Sticky) --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('homeIn') }}" class="text-decoration-none me-3 text-brown" style="font-size: 1.5rem;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0 text-brown">
                    {{ Auth::user()->is_seller ? 'Chat Buyer' : 'Chat Seller' }}
                </h5>
            </div>
        </div>
    </div>

{{-- 1. Use container-fluid and g-0. NOTE: Default container-fluid padding is now active --}}
<div class="container-fluid chat-container-wrapper">
    <div class="row g-0">

        {{-- Left Panel: Chat List (col-md-4) --}}
        <div class="col-12 col-md-4 border-end chat-list-container">
            <div class="list-group list-group-flush">
                @if ($chats->isEmpty()) 
                    <p class="text-center p-4 text-brown">You have no active conversations.</p>
                @else
                    @foreach ($chats as $currentChat)
                        @php
                            $otherUser = (Auth::id() == $currentChat->user1_id) ? $currentChat->user2 : $currentChat->user1;
                            $lastMessage = $currentChat->messages->first();
                            $isActive = ($chat->id == $currentChat->id); 
                            $profileImageUrl = $otherUser->profile_photo 
                                               ? asset('storage/' . $otherUser->profile_photo) 
                                               : asset('default-avatar.jpg');
                        @endphp

                        <a href="{{ route('chat.show', $otherUser->id) }}" 
                           class="list-group-item list-group-item-action d-flex align-items-center p-3 text-brown {{ $isActive ? 'active' : '' }}">
                            
                            <img src="{{ $profileImageUrl }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            
                            <div>
                                <h6 class="mb-0 fw-bold text-brown">{{ $otherUser->name }}</h6>
                                <small class="text-truncate text-brown" style="display: block; max-width: 90%;">
                                    @if ($lastMessage)
                                        {{ Str::limit($lastMessage->content, 30) }}
                                    @else
                                        Start a conversation.
                                    @endif
                                </small>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Right Panel: Main Chat Content (col-md-8) --}}
        <div class="col-12 col-md-8 d-flex flex-column" style="background-color: var(--color-page-bg);">
            
            {{-- Chat Header (Top) --}}
            {{-- Uses px-3 for horizontal padding --}}
            <div class="d-flex align-items-center p-3 border-bottom px-3" style="background-color: var(--color-chat-list-bg);">
                <h5 class="fw-bold mb-0 text-brown">{{ $receiver->name }}</h5>
            </div>

            {{-- 4. Message Area (Scrollable History) --}}
            {{-- Uses px-3 for horizontal padding --}}
            <div class="flex-grow-1 message-area px-3">
                @if ($messages->isEmpty())
                    <p class="text-center text-brown pt-5">Say hello to start your conversation!</p>
                @else
                    @php 
                        $currentDate = null; 
                    @endphp
                    @foreach ($messages as $message)
                        @php
                            $messageDate = $message->created_at->translatedFormat('l, j F Y');
                            if ($messageDate !== $currentDate) {
                                $currentDate = $messageDate;
                                echo '<p class="text-center my-3"><span class="badge bg-secondary">' . $currentDate . '</span></p>';
                            }
                            $isSender = $message->sender_id === Auth::id();
                        @endphp
                        
                        {{-- Message Bubble --}}
                        <div class="d-flex {{ $isSender ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-2 rounded-3 text-break {{ $isSender ? 'message-bubble-sender' : 'message-bubble-receiver' }}" 
                                 style="max-width: 75%;">
                                {{ $message->content }}
                                <small class="text-end d-block mt-1 sender-timestamp">
                                    {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                    {{-- Spacer to ensure the last message is visible above the fixed input --}}
                    <div style="height: 60px;"></div>
                @endif
            </div>

{{-- 4. Message Input Footer (Fixed to Bottom) --}}
            <div class="chat-input-footer">
                {{-- Form uses w-100, d-flex, and py-2 for vertical padding (bottom gap) --}}
                <form action="{{ route('chat.store', $chat->id) }}" method="POST" class="d-flex w-100 py-2"> 
                    @csrf
                    
                    {{-- Input wrapper uses col-10 on desktop and ps-3 for left padding (left gap) --}}
                    <div class="col-10 d-flex ps-3"> 
                        <input type="text" 
                               name="content" 
                               class="form-control me-2 @error('content') is-invalid @enderror" 
                               placeholder="Tulis Pesan" 
                               required>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Button uses col-2 for the remaining space and pe-3 for right padding (right gap) --}}
                    <div class="col-2 d-flex justify-content-end pe-3"> 
                        <button type="submit" class="btn" style="background-color: #f79471; color: white;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
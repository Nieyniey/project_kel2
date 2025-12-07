@extends('layouts.app') 

@section('title', 'Chat with ' . $receiver->name)

@section('content')
<style>
    :root {
        --color-page-bg: #E8E0BB; 
        --color-chat-list-bg: #FFFBE8; 
        --color-brown: #6C2207; 
        --color-white: #FFFEF7; 
        --color-primary-orange: #FC5801; /* New Primary Color */
        --color-primary-darker-40: #9B3600; /* ~40% darker of #FC5801 */
    }

    body {
        background-color: var(--color-chat-list-bg);
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
        background-color: white !important; 
        border-right: 2px solid var(--color-brown);
    }

    .list-group-item {
        background-color: var(--color-chat-list-bg);
        border: none;
        border-bottom: 1px solid rgba(108, 34, 7, 0.2);
    }

    .message-area {
        background-color: var(--color-page-bg);
        max-height: calc(100vh - 140px); 
        overflow-y: auto; 
        padding-bottom: 70px; 
    }
    
    .chat-input-footer {
        background-color: var(--color-white) !important;
        position: fixed;
        bottom: 0;
        z-index: 999; 
        border-top: 1px solid rgba(0, 0, 0, 0.1); 
        left: 0;
        right: 0; 
        width: 100%; 
    }

    @media (min-width: 768px) {
        .chat-input-footer {
            left: 33.333333%; 
            right: 0; 
            width: auto !important; 
        }
    }
    
    .message-bubble-sender {
        background-color: var(--color-white) !important;
        color: var(--color-brown) !important;
    }

    .message-bubble-receiver {
        background-color: #FFFFFF !important;
        color: var(--color-brown) !important;
    }

    .sender-timestamp {
        color: rgba(108, 34, 7, 0.7) !important;
    }

    .date-badge {
        background-color: var(--color-primary-orange) !important;
    }

    .btn-send {
        background-color: var(--color-primary-orange) !important;
        color: var(--color-white);
        transition: background-color 0.2s ease;
        border: none; /* Ensure no border interferes with the background */
    }

    .btn-send:hover {
        background-color: var(--color-primary-darker-40) !important;
        color: var(--color-white);
    }

    .chat-input-footer {
        background-color: var(--color-white) !important;
        position: fixed;
        bottom: 0;
        z-index: 999;
        width: 100%;
        left: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    @media (min-width: 768px) {
        .chat-input-footer {
            width: calc(100% - 33.333333%); 
            left: 33.333333%;
        }
    }

    .chat-container-wrapper,
    .chat-container-wrapper .row,
    .chat-container-wrapper .col-12,
    .chat-container-wrapper .col-md-8,
    .chat-container-wrapper .col-md-4 {
        padding-left: 0 !important;
        padding-right: 0 !important;
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
            <div class="d-flex align-items-center p-3 border-bottom px-3" style="background-color: var(--color-chat-list-bg);">
                <h5 class="fw-bold mb-0 text-brown">{{ $receiver->name }}</h5>
            </div>

            {{-- 4. Message Area (Scrollable History) --}}
            <div class="flex-grow-1 message-area">
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
                                echo '<p class="text-center my-3"><span class="badge date-badge">' . $currentDate . '</span></p>';
                            }
                            $isSender = $message->sender_id === Auth::id();
                        @endphp
                        
                        {{-- Message Bubble --}}
                        <div class="d-flex {{ $isSender ? 'justify-content-end' : 'justify-content-start' }} mb-2"
                            style="{{ $isSender ? 'margin-right: 12px;' : 'margin-left: 12px;' }}">
                            
                            <div class="p-2 rounded-3 text-break 
                                        {{ $isSender ? 'message-bubble-sender' : 'message-bubble-receiver' }}"
                                style="max-width: 75%;">

                                {{ $message->content }}

                                <small class="text-end d-block mt-1 sender-timestamp">
                                    {{ $message->created_at->format('H:i') }}
                                </small>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- 4. Message Input Footer (Fixed to Bottom) --}}
            <div class="chat-input-footer">
                <form action="{{ route('chat.store', $chat->id) }}" method="POST" class="d-flex w-100 py-2"> 
                    @csrf
                    <div class="d-flex w-100 px-3">
                        <input type="text" 
                               name="content" 
                               class="form-control me-2 @error('content') is-invalid @enderror" 
                               placeholder="Tulis Pesan" 
                               required>
                        
                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script to scroll to the bottom of the message area on load --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageArea = document.querySelector('.message-area');
        if (messageArea) {
            messageArea.scrollTop = messageArea.scrollHeight;
        }
    });
</script>
@endsection
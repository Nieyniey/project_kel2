@extends('layouts.app') 

@section('title', 'Chat')

@section('content')

<style>
    :root {
        --color-main-text: #6C2207; 
        --color-page-bg: #FFFBE8; 
        --color-list-bg: #FFFEF7; 
        --color-main-panel-bg: #E8E0BB; 
    }

    body {
        color: var(--color-main-text);
        background-color: var(--color-page-bg);
    }
    
    .text-main {
        color: var(--color-main-text) !important;
    }
    
    .text-dark {
        color: var(--color-main-text) !important; 
    }

    .header-fixed {
        background-color: var(--color-list-bg); 
        width: 100%;
        position: sticky; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    .chat-content-area {
        max-height: calc(100vh - 60px); 
        overflow: hidden; 
    }
    
    .chat-list-panel {
        background-color: var(--color-list-bg);
        max-height: calc(100vh - 60px); 
        overflow-y: auto; 
    }

    .chat-main-panel {
        background-color: var(--color-main-panel-bg);
        max-height: calc(100vh - 60px); 
    }

    .list-group-item-action {
        transition: background-color 0.2s;
    }
    
    .list-group-item.bg-light {
        background-color: var(--color-page-bg) !important; 
    }

    .text-muted {
        color: rgba(108, 34, 7, 0.7) !important;
    }
</style>

{{-- Header: Back Button and Title --}}
    <div class="header-fixed">
        <div class="container"> 
            <div class="d-flex align-items-center">
                <a href="{{ route('homeIn') }}" class="text-decoration-none me-3" style="font-size: 1.5rem; color:#FC5801!important;">
                    &leftarrow;
                </a>
                <h5 class="fw-bold mb-0" style="color: #6C2207;">
                    {{ Auth::user()->is_seller ? 'Chat Buyer' : 'Chat Seller' }}
                </h5>
            </div>
        </div>
    </div>

{{-- === 2. THE MAIN SCROLLABLE CONTENT AREA === --}}
<div class="container-fluid chat-content-area p-0">
    <div class="row g-0 h-100">
        {{-- Chat List Panel (Left) --}}
        <div class="col-12 col-md-4 border-end chat-list-panel">
            {{-- Content previously here is now in the fixed header --}}

            @if ($chats->isEmpty())
                <p class="text-center p-4 text-muted">You have no active conversations.</p>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($chats as $chat)
                        @php
                            $otherUser = (Auth::id() == $chat->user1_id) ? $chat->user2 : $chat->user1;
                            $lastMessage = $chat->messages->first(); 
                            $isActive = (isset($currentChat) && $currentChat->id == $chat->id);
                            
                            $profileImageUrl = $otherUser->profile_photo 
                                ? asset('storage/' . $otherUser->profile_photo) 
                                : asset('default-avatar.jpg'); // Ensure this fallback exists
                        @endphp

                        {{-- Chat Item --}}
                        <a href="{{ route('chat.show', $otherUser->id) }}"
                           class="list-group-item list-group-item-action d-flex align-items-center p-3 text-dark border-bottom {{ $isActive ? 'bg-light' : '' }}" 
                           aria-current="{{ $isActive ? 'true' : 'false' }}">
                            
                            <img src="{{ $profileImageUrl }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold text-truncate">{{ $otherUser->name }}</h6>
                                <small class="text-truncate" style="display: block; max-width: 90%;">
                                    @if ($lastMessage)
                                        {{ Str::limit($lastMessage->content, 30) }}
                                    @else
                                        Start a conversation.
                                    @endif
                                </small>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Main Chat Content (Right Panel) - Empty on index page --}}
        <div class="col-12 col-md-8 d-none d-md-block chat-main-panel">
            <div class="d-flex align-items-center justify-content-center h-100">
                <p class="text-muted">Select a chat to start messaging.</p>
            </div>
        </div>
    </div>
</div>
@endsection
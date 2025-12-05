@extends('layouts.app') 

@section('title', 'Chat')

@section('content')

<style>
    /* STYLES FOR THE FIXED HEADER CONTAINER (Copied from Wishlist) */
    .chat-header-fixed {
        background-color: #FFFEF7; /* The desired color, assuming the wishlist color */
        width: 100%;
        position: fixed; 
        top: 0;
        left: 0;
        z-index: 1000; 
        padding: 15px 0; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    /* STYLE FOR THE MAIN CHAT CONTENT PUSH */
    .chat-content-area {
        /* Add margin-top equal to the height of the fixed header (approx 60px) */
        margin-top: 60px; 
        /* Set max height for the chat body to enable scrolling within the panel */
        max-height: calc(100vh - 60px); 
        overflow: hidden; /* Prevent the entire body from scrolling unnecessarily */
    }
    
    /* Styling for the Left Panel (Chat List) */
    .chat-list-panel {
        background-color: #f7e6d1;
        max-height: calc(100vh - 60px); /* Adjust max-height based on header height */
        overflow-y: auto; /* Enable scrolling for the list */
    }

    /* Styling for the Right Panel (Default Chat View) */
    .chat-main-panel {
        background-color: #fff9f0;
        max-height: calc(100vh - 60px); /* Adjust max-height based on header height */
    }

    /* Custom style for the Chat List Header (Based on image_76cda3.png) */
    .chat-list-header {
        background-color: #5c4a3e; /* Choose a color that matches your design, this is a placeholder */
        color: white;
        padding: 15px;
    }

    /* Adjusting the chat list item appearance */
    .list-group-item-action {
        transition: background-color 0.2s;
    }

    /* Ensure active chat item is highlighted */
    .list-group-item.bg-light {
        background-color: #FFFBE8 !important; /* Lighter shade of background for active */
    }
</style>

{{-- === 1. THE FULL-WIDTH FIXED HEADER === --}}
<div class="chat-header-fixed">
    <div class="container-fluid"> {{-- Use container-fluid for full width if possible, or standard container for alignment --}}
        <div class="d-flex align-items-center">
            <a href="{{ url()->previous() }}" class="text-dark me-3" style="font-size: 1.5rem; color:#FF6E00!important; text-decoration:none!important">
                ←
            </a>
            {{-- Title changes based on user role (Buyer or Seller) --}}
            <h5 class="fw-bold mb-0" style="color: #6C2207;">
                {{ Auth::user()->is_seller ? 'Chat Buyer' : 'Chat Seller' }}
            </h5>
        </div>
    </div>
</div>
{{-- =================================== --}}

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
                        <a href="{{ route('chat.show.user', $otherUser->id) }}" 
                           class="list-group-item list-group-item-action d-flex align-items-center p-3 text-dark border-bottom {{ $isActive ? 'bg-light' : '' }}" 
                           aria-current="{{ $isActive ? 'true' : 'false' }}">
                            
                            <img src="{{ $profileImageUrl }}" 
                                 alt="{{ $otherUser->name }}'s Profile" 
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
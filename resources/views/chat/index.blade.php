@extends('layouts.Main') 

@section('title', 'Chat')

@section('content')
<div class="container-fluid py-5">
    <div class="row g-0">
        {{-- Chat List Panel (Based on image_76cda3.png) --}}
        <div class="col-12 col-md-4 border-end" style="background-color: #f7e6d1; max-height: 80vh; overflow-y: auto;">
            <div class="d-flex align-items-center p-3 border-bottom" style="background-color: #f79471;">
                <a href="{{ url()->previous() }}" class="text-white me-3" style="font-size: 1.5rem;">
                    <i class="bi bi-arrow-left"></i> 
                </a>
                <h4 class="fw-bold mb-0 text-white">Chat Seller</h4> 
                {{-- Title is "Chat Seller" in image_76cda3.png, assuming this is the primary chat view --}}
            </div>

            @if ($chats->isEmpty())
                <p class="text-center p-4 text-muted">You have no active conversations.</p>
            @else
                @foreach ($chats as $chat)
                    {{-- Determine the other user in the chat --}}
                    @php
                        $otherUser = (Auth::id() == $chat->user1_id) ? $chat->user2 : $chat->user1;
                        $lastMessage = $chat->messages->first(); // Due to ->latest()->limit(1) in the Controller
                        $isActive = (isset($currentChat) && $currentChat->id == $chat->id);
                        // Assuming $otherUser->profile_photo exists, otherwise use a placeholder
                        $profileImageUrl = $otherUser->profile_photo 
                                           ? asset('storage/' . $otherUser->profile_photo) 
                                           : asset('default-avatar.jpg');
                    @endphp

                    {{-- Chat Item --}}
                    <a href="{{ route('chat.show', $chat->id) }}" 
                       class="list-group-item list-group-item-action d-flex align-items-center p-3 text-dark {{ $isActive ? 'bg-light' : '' }}" 
                       aria-current="{{ $isActive ? 'true' : 'false' }}">
                        
                        <img src="{{ $profileImageUrl }}" 
                             alt="{{ $otherUser->name }}'s Profile" 
                             class="rounded-circle me-3" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                        
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $otherUser->name }}</h6>
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
            @endif
        </div>

        {{-- Main Chat Content (Right Panel) - Empty on index page --}}
        <div class="col-12 col-md-8 d-none d-md-block" style="background-color: #fff9f0;">
            <div class="d-flex align-items-center justify-content-center h-100">
                <p class="text-muted">Select a chat to start messaging.</p>
            </div>
        </div>
    </div>
</div>
@endsection
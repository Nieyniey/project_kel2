@extends('layouts.Main') 

@section('title', 'Chat with ' . $receiver->name)

@section('content')
<div class="container-fluid py-5">
    <div class="row g-0">
        {{-- Chat List Panel (Same as index.blade.php) --}}
        <div class="col-12 col-md-4 border-end" style="background-color: #f7e6d1; max-height: 80vh; overflow-y: auto;">
            <div class="d-flex align-items-center p-3 border-bottom" style="background-color: #f79471;">
                <a href="{{ route('chat.index') }}" class="text-white me-3" style="font-size: 1.5rem;">
                    <i class="bi bi-arrow-left"></i> 
                </a>
                <h4 class="fw-bold mb-0 text-white">Chat Seller</h4> 
            </div>

            @if ($chats->isEmpty()) 
                <p class="text-center p-4 text-muted">You have no active conversations.</p>
            @else
                {{-- Note: $chats variable must be passed from the controller if we want the full list here.
                     Since the controller only loads one chat, we'll assume the index list is embedded here
                     or we redirect back to the index view for list selection. 
                     For simplicity, we'll use a dummy loop here and assume the full $chats list is available. --}}

                @foreach ($chats as $currentChat)
                    @php
                        $otherUser = (Auth::id() == $currentChat->user1_id) ? $currentChat->user2 : $currentChat->user1;
                        $lastMessage = $currentChat->messages->first();
                        $isActive = ($chat->id == $currentChat->id); // Highlight the current chat
                        $profileImageUrl = $otherUser->profile_photo 
                                           ? asset('storage/' . $otherUser->profile_photo) 
                                           : asset('default-avatar.jpg');
                    @endphp

                    <a href="{{ route('chat.show', $currentChat->id) }}" 
                       class="list-group-item list-group-item-action d-flex align-items-center p-3 text-dark {{ $isActive ? 'bg-light' : '' }}">
                        
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

        {{-- Main Chat Content (Right Panel) --}}
        <div class="col-12 col-md-8" style="background-color: #fff9f0;">
            {{-- Chat Header --}}
            <div class="d-flex align-items-center p-3 border-bottom" style="background-color: #f7e6d1;">
                <h5 class="fw-bold mb-0">{{ $receiver->name }}</h5>
            </div>

            {{-- Message Area --}}
            <div class="p-3" style="max-height: 65vh; overflow-y: auto;">
                @if ($messages->isEmpty())
                    <p class="text-center text-muted">Say hello to start your conversation!</p>
                @else
                    @php 
                        $currentDate = null; 
                    @endphp
                    @foreach ($messages as $message)
                        @php
                            $messageDate = $message->created_at->translatedFormat('l, j F Y');
                            if ($messageDate !== $currentDate) {
                                $currentDate = $messageDate;
                                // Display Date Separator (like "Selasa" in image_76cda3.png)
                                echo '<p class="text-center my-3"><span class="badge bg-secondary text-white">' . $currentDate . '</span></p>';
                            }
                            $isSender = $message->sender_id === Auth::id();
                        @endphp
                        
                        {{-- Message Bubble --}}
                        <div class="d-flex {{ $isSender ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-2 rounded-3 text-break" 
                                 style="max-width: 75%; 
                                        background-color: {{ $isSender ? '#f79471' : '#e5d8c6' }};
                                        color: {{ $isSender ? 'white' : 'black' }};">
                                {{ $message->content }}
                                <small class="text-end d-block mt-1" style="font-size: 0.75em; color: {{ $isSender ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.5)' }};">
                                    {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Message Input Footer --}}
            <div class="p-3 border-top" style="background-color: white;">
                <form action="{{ route('chat.store', $chat->id) }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" 
                           name="content" 
                           class="form-control me-2 @error('content') is-invalid @enderror" 
                           placeholder="Tulis Pesan" 
                           required>
                    <button type="submit" class="btn" style="background-color: #f79471; color: white;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
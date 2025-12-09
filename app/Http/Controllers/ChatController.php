<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChatController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     ->with(['user1', 'user2', 'messages' => function ($query) {
                        $query->latest()->limit(1);
                     }])
                     ->get();

        return view('chat.index', compact('chats'));
    }

    public function show(int $receiverId)
    {
        $senderId = Auth::id(); 

        $receiver = User::find($receiverId);
        if (!$receiver) {
            return redirect()->route('chat.index')->with('error', 'Penerima pesan tidak ditemukan.');
        }

        $chat = Chat::where(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $senderId)
                  ->where('user2_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $receiverId)
                  ->where('user2_id', $senderId);
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $senderId, 
                'user2_id' => $receiverId,
            ]);
        }
        
        $messages = $chat->messages()->with('sender')->get();

        $chat->messages()->where('sender_id', '!=', $senderId)->update(['is_read' => true]);

        $userId = Auth::id();
        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     ->with(['user1', 'user2', 'messages' => function ($query) {
                         $query->latest()->limit(1);
                     }])
                     ->get();

        return view('chat.show', compact('chat', 'messages', 'receiver', 'chats')); 
    }

    public function store(Request $request, Chat $chat)
    {
        $request->validate(['content' => 'required|string|max:500']);
        
        if ($chat->user1_id !== Auth::id() && $chat->user2_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengirim pesan di chat room ini.');
        }

        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        $receiverId = ($chat->user1_id === Auth::id()) ? $chat->user2_id : $chat->user1_id;
        return redirect()->route('chat.show', $receiverId)->with('success', 'Pesan terkirim!');
    }
    
}
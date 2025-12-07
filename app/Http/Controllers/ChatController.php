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

    /**
     * 1. Menampilkan daftar semua chat room user yang sedang login.
     */
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

    /**
     * 2. Menampilkan chat room spesifik atau MEMBUAT room baru.
     * @param int $receiverId - ID user lawan chat (seller atau buyer)
     */
    public function show(int $receiverId) // Original signature: expects an ID
    {
        $senderId = Auth::id(); 

        // 1. Cek apakah lawan chat nya ada
        $receiver = User::find($receiverId);
        if (!$receiver) {
            return redirect()->route('chat.index')->with('error', 'Penerima pesan tidak ditemukan.');
        }

        // 2. Cari atau buat chat room
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
        
        // 3. Ambil semua pesan di chat room
        $messages = $chat->messages()->with('sender')->get();

        // 4. Tandai semua pesan yang diterima sebagai sudah dibaca
        $chat->messages()->where('sender_id', '!=', $senderId)->update(['is_read' => true]);

        
        // 5. FIX FOR UNDEFINED $CHATS ERROR: Load the list of all active chats for the sidebar
        // This is necessary because the view chat.show expects $chats to display the chat list.
        $userId = Auth::id();
        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     // You may need to eager load here as well, depending on your sidebar content
                     ->with(['user1', 'user2', 'messages' => function ($query) {
                         $query->latest()->limit(1);
                     }])
                     ->get();

        // 6. Asumsi: View untuk room chat adalah 'chat.show'
        return view('chat.show', compact('chat', 'messages', 'receiver', 'chats')); 
    }

    /**
     * 3. Menyimpan pesan baru ke database (Mengirim Pesan).
     */
    public function store(Request $request, Chat $chat)
    {
        // 1. Validasi input
        $request->validate(['content' => 'required|string|max:500']);
        
        // 2. Cek otorisasi
        if ($chat->user1_id !== Auth::id() && $chat->user2_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengirim pesan di chat room ini.');
        }

        // 3. Simpan pesan
        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        $receiverId = ($chat->user1_id === Auth::id()) ? $chat->user2_id : $chat->user1_id;
        return redirect()->route('chat.show', $receiverId)->with('success', 'Pesan terkirim!');
    }
    
}
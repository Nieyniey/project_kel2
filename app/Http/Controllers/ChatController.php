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
        $userId = Auth::id(); // Auth::id() adalah helper untuk mengambil ID user yang sedang login

        // Ambil semua chat dimana user ini adalah user1 ATAU user2
        $chats = Chat::where('user1_id', $userId)
                     ->orWhere('user2_id', $userId)
                     // Dengan pesan terakhir dan info (nama, atau info lainnya) dari pihak lawannya (user satunya yang terlibat di chat yang sama)
                     ->with(['user1', 'user2', 'messages' => function ($query) {
                        $query->latest()->limit(1); // Ambil pesan terakhir saja
                     }])
                     ->get();

                     /** 
                      * with() adalah fitur Eloquent yang disebut Eager Loading. 
                      * Ini memberitahu Laravel untuk mengambil data Model User yang terhubung 
                      * (baik user1 maupun user2) pada saat yang sama dengan mengambil data Chat.
                      */

        // Asumsi: View untuk daftar chat adalah 'chat.index'
        return view('chat.index', compact('chats'));
    }

    /**
     * 2. Menampilkan chat room spesifik atau MEMBUAT room baru.
     * * @param int $receiverId - ID user lawan chat (seller atau buyer)
     */
    public function show(int $receiverId)
    {
        $senderId = Auth::id(); 

        // Cek apakah lawan chat nya ada (terdaftar sebagai user juga)
        $receiver = User::find($receiverId);
        if (!$receiver) {
            return redirect()->route('chat.index')->with('error', 'Penerima pesan tidak ditemukan.');
        }

        // Cari chat yang sudah ada antara SENDER dan RECEIVER (baik SENDER sebagai user1 atau user2)
        // Ini penting untuk memastikan tidak ada duplikasi chat room.
        $chat = Chat::where(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $senderId)
                  ->where('user2_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $receiverId)
                  ->where('user2_id', $senderId);
        })->first();

        // Jika chat belum ada, buat room baru
        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $senderId, 
                'user2_id' => $receiverId,
            ]);
        }
        
        // Ambil semua pesan di chat room ini, diurutkan dari yang terbaru (ASC)
        $messages = $chat->messages()->with('sender')->get();

        // Tandai semua pesan yang diterima sebagai sudah dibaca
        $chat->messages()->where('sender_id', '!=', $senderId)->update(['is_read' => true]);

        // Asumsi: View untuk room chat adalah 'chat.show'
        return view('chat.show', compact('chat', 'messages', 'receiver'));
    }

    /**
     * 3. Menyimpan pesan baru ke database (Mengirim Pesan).
     */
    public function store(Request $request, Chat $chat)
    {
        // 1. Validasi input
        $request->validate(['content' => 'required|string|max:500']);
        
        // 2. Cek otorisasi: pastikan user yang mengirim pesan adalah salah satu anggota chat room
        if ($chat->user1_id !== Auth::id() && $chat->user2_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengirim pesan di chat room ini.');
        }

        // 3. Simpan pesan
        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        // Catatan: Di aplikasi real-time, Anda akan mengirim broadcast event di sini.
        
        // Redirect kembali ke halaman chat room
        return redirect()->route('chat.show', $chat->id)->with('success', 'Pesan terkirim!');
    }
}
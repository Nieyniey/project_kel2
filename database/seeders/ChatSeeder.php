<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Faker\Factory as Faker; // <<< Import Faker Factory

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        // Inisialisasi Faker
        $faker = Faker::create();

        $users = User::all();
        if ($users->count() < 2) {
            // $this->command->warn('Skip ChatSeeder: Need at least 2 users.'); // Opsional
            return;
        }
        
        $usersArray = $users->pluck('id')->toArray();

        // Ambil 5 pasangan user unik untuk memulai chat
        $chatPairs = collect();
        while ($chatPairs->count() < 5) {
            // Ganti $this->faker()->... dengan $faker->...
            $user1Id = $faker->randomElement($usersArray);
            $user2Id = $faker->randomElement($usersArray);

            // Pastikan user berbeda dan pasangan unik (1, 2) sama dengan (2, 1)
            if ($user1Id !== $user2Id && !$chatPairs->contains(fn($pair) => ($pair[0] === $user1Id && $pair[1] === $user2Id) || ($pair[0] === $user2Id && $pair[1] === $user1Id))) {
                $chatPairs->push([$user1Id, $user2Id]);
            }
        }

        // Buat Chat Room dan Isi Pesan
        $chatPairs->each(function ($pair) use ($faker) { // <<< Jangan lupa use ($faker)
            $chat = Chat::create([
                'user1_id' => $pair[0],
                'user2_id' => $pair[1],
            ]);

            // Isi 10 hingga 20 pesan di setiap room
            for ($i = 0; $i < $faker->numberBetween(10, 20); $i++) {
                $senderId = $faker->randomElement([$pair[0], $pair[1]]);
                
                Message::create([
                    'chat_id' => $chat->id,
                    'sender_id' => $senderId,
                    'content' => $faker->sentence,
                    'is_read' => $faker->boolean(80), // 80% kemungkinan sudah dibaca
                    'created_at' => now()->subMinutes($i * 5),
                    'updated_at' => now()->subMinutes($i * 5),
                ]);
            }
        });
    }
}
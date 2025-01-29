<?php

namespace Database\Seeders\Chat;

use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $firstUser = User::find(1);
        $secondUser = User::find(2);

        $messages = [
            [
                'content' => 'Hello, how are you?',
                'user_id' => $firstUser->id,
            ],
            [
                'content' => 'I am fine, thank you!',
                'user_id' => $secondUser->id,
            ],
            [
                'content' => 'How is your day going?',
                'user_id' => $firstUser->id,
            ],
            [
                'content' => 'Not too bad, thanks!',
                'user_id' => $secondUser->id,
            ],
        ];

        $chat = Chat::forceCreate([
            'name' => 'Chat with '.$secondUser->name,
            'is_group_chat' => false,
        ]);

        $chat->participants()->createMany([
            ['user_id' => $firstUser->id],
            ['user_id' => $secondUser->id],
        ]);

        foreach ($messages as $message) {
            Message::forceCreate([
                'content' => $message['content'],
                'user_id' => $message['user_id'],
                'chat_id' => $chat->id,
            ]);
        }
    }
}

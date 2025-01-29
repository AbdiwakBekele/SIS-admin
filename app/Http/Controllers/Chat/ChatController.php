<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatResource;
use App\Models\Chat\Chat;
use App\Models\Chat\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $name = $request->query('name');

        $chats = Chat::query()
            ->whereHas('participants', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['participants.user', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('user_id', '!=', auth()->id())
                    ->whereNull('read_at');
            }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('messages', function ($query) use ($search) {
                        $query->where('content', 'like', "%{$search}%");
                    })->orWhere(function ($q) use ($search) {
                        $q->whereIsGroupChat(true)
                            ->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('participants.user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->where('id', '!=', auth()->id());
                        });
                });
            })
            ->orderBy('last_messaged_at', 'desc')
            ->cursorPaginate(10);

        return ChatResource::collection($chats);
    }

    public function show(Chat $chat)
    {
        $chat->messages()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return ChatResource::make($chat);
    }

    private function getExistingChat(array $participantIds = [])
    {
        $chats = Participant::query()
            ->select('chat_id')
            ->whereIn('user_id', $participantIds)
            ->groupBy('chat_id')
            ->havingRaw('COUNT(*) = ?', [count($participantIds)])
            ->get();

        foreach ($chats as $chat) {
            $existingChatParticipants = Participant::query()
                ->where('chat_id', $chat->chat_id)
                ->pluck('user_id')
                ->toArray();

            if (! array_diff($existingChatParticipants, $participantIds)) {
                return $chat->chat_id;
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'is_group_chat' => 'boolean',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,uuid',
        ]);

        $participantUuids = array_merge($validated['participants'], [Auth::user()->uuid]);

        $participantIds = User::whereIn('uuid', $participantUuids)->pluck('id')->all();

        $existingChat = $this->getExistingChat($participantIds);

        $chat = $existingChat ? Chat::find($existingChat) : Chat::firstOrCreate([
            'name' => $validated['name'],
            'is_group_chat' => (int) Arr::get($validated, 'is_group_chat', false),
        ]);

        if (! $existingChat) {
            $chat->last_messaged_at = now()->toDateTimeString();
            $chat->save();
        }

        if (! $existingChat) {
            $participants = array_map(function ($participantId) {
                return ['user_id' => $participantId];
            }, $participantIds);

            $chat->participants()->createMany($participants);
        }

        return response()->json(ChatResource::make($chat->load('participants.user')), 201);
    }

    public function markAsRead(Chat $chat)
    {
        $chat->messages()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->ok([]);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();

        return response()->ok([]);
    }
}

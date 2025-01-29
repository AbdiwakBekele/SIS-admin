<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::query()
            ->select('users.*')
            ->where('users.id', '!=', auth()->id())
            ->where('name', 'like', '%'.$request->input('query').'%')
            ->get();

        return $users->map(function ($user) {
            return [
                'uuid' => $user->uuid,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'self' => $user->id === auth()->id(),
            ];
        });
    }
}

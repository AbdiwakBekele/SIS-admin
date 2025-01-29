<?php

namespace App\Actions\Config;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TestServerConnection
{
    public function execute(Request $request)
    {
        if (config('config.server.platform') != 'cpanel') {
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => 'cpanel '.config('config.server.cpanel_username').':'.config('config.server.cpanel_api_token'),
        ])->get('https://'.config('config.server.cpanel_hostname').':'.config('config.server.cpanel_port').'/execute/Mysql/get_restrictions');

        if (empty($response->json())) {
            throw ValidationException::withMessages(['message' => $response->body()]);
        }

        $response = $response->json();

        if (! Arr::get($response, 'status')) {
            throw ValidationException::withMessages(['message' => Arr::first(Arr::get($response, 'errors'))]);
        }

        if (Arr::get($response, 'data.prefix') != config('config.server.db_prefix')) {
            throw ValidationException::withMessages(['message' => __('config.server.invalid_db_prefix')]);
        }
    }
}

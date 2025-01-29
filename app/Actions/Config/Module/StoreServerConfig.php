<?php

namespace App\Actions\Config\Module;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StoreServerConfig
{
    public static function handle(): array
    {
        $input = request()->validate([
            'platform' => 'required|in:local,cpanel',
            'cpanel_hostname' => 'nullable|required_if:platform,cpanel|string',
            'cpanel_port' => 'nullable|required_if:platform,cpanel|integer',
            'cpanel_username' => 'nullable|required_if:platform,cpanel|string|max:20',
            'cpanel_api_token' => 'nullable|required_if:platform,cpanel|string|max:50',
            'db_prefix' => 'required|string|max:20',
        ], [
            'cpanel_hostname.required_if' => __('validation.required', ['attribute' => __('config.server.props.cpanel_hostname')]),
            'cpanel_port.required_if' => __('validation.required', ['attribute' => __('config.server.props.cpanel_port')]),
            'cpanel_username.required_if' => __('validation.required', ['attribute' => __('config.server.props.cpanel_username')]),
            'cpanel_api_token.required_if' => __('validation.required', ['attribute' => __('config.server.props.cpanel_api_token')]),
            'db_prefix.required_if' => __('validation.required', ['attribute' => __('config.server.props.db_prefix')]),
        ], [
            'platform' => __('config.server.props.platform'),
        ]);

        if (! Str::endsWith(Arr::get($input, 'db_prefix'), '_')) {
            throw ValidationException::withMessages(['db_prefix' => __('config.server.db_prefix_no_trailing_underscore')]);
        }

        return $input;
    }
}

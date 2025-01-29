<?php

namespace App\Actions\Config\Module;

class StoreSiteConfig
{
    public static function handle(): array
    {
        $input = request()->validate([
            'enable_site' => 'boolean',
            'support_email' => 'nullable|email|max:100',
        ], [], [
            'enable_site' => __('global.enable', ['attribute' => __('site.site')]),
            'support_email' => __('site.config.support_email'),
        ]);

        return $input;
    }
}

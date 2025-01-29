<?php

namespace Mint\Service\Actions;

use App\Helpers\SysHelper;
use Closure;
use Illuminate\Support\Arr;

class SetENV
{
    public function handle($params, Closure $next)
    {
        if (env('APP_ENV') === 'testing') {
            return $next($params);
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $host = Arr::get(parse_url($_SERVER['HTTP_HOST']), 'path');
        $isIP = filter_var($host, FILTER_VALIDATE_IP) ? true : false;
        $host = str_replace('www.', '', $host);
        $sessionDomain = $isIP ? $host : ('.' . $host);
        $sanctumStatefulDomain = $isIP ? $host : ($host . ',www.' . $host);

        SysHelper::setEnv([
            'APP_URL'                  => $protocol . $host,
            'DB_PORT'                  => Arr::get($params, 'db_port'),
            'DB_HOST'                  => Arr::get($params, 'db_host'),
            'DB_DATABASE'              => Arr::get($params, 'db_name'),
            'DB_USERNAME'              => Arr::get($params, 'db_username'),
            'DB_PASSWORD'              => Arr::get($params, 'db_password'),
            'SESSION_DOMAIN'           => null,
            'SANCTUM_STATEFUL_DOMAINS' => $sanctumStatefulDomain,
        ]);

        SysHelper::setEnv([
            'APP_URL'                  => $protocol.$host,
            'DB_PORT'                  => Arr::get($params, 'db_port'),
            'DB_HOST'                  => Arr::get($params, 'db_host'),
            'DB_DATABASE'              => Arr::get($params, 'db_name'),
            'DB_USERNAME'              => Arr::get($params, 'db_username'),
            'DB_PASSWORD'              => Arr::get($params, 'db_password'),
            'SESSION_DOMAIN'           => null,
            'SANCTUM_STATEFUL_DOMAINS' => $sanctumStatefulDomain,
        ], SysHelper::getTenantPath('.env'));

        config(['app.env' => 'local']);
        config(['telescope.enabled' => false]);

        \DB::purge('landlord');

        config([
            'database.connections.landlord.host' => Arr::get($params, 'db_host'),
            'database.connections.landlord.port' => Arr::get($params, 'db_port'),
            'database.connections.landlord.database' => Arr::get($params, 'db_name'),
            'database.connections.landlord.username' => Arr::get($params, 'db_username'),
            'database.connections.landlord.password' => Arr::get($params, 'db_password')
        ]);

        \DB::reconnect('landlord');

        return $next($params);
    }
}

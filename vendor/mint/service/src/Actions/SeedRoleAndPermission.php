<?php

namespace Mint\Service\Actions;

use Closure;

class SeedRoleAndPermission
{
    public function handle($params, Closure $next)
    {
        \Artisan::call('db:seed', ['--class' => 'RoleSeeder', '--force' => true]);
        \Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
        \Artisan::call('db:seed', ['--class' => 'AssignPermissionSeeder', '--force' => true]);

        return $next($params);
    }
}

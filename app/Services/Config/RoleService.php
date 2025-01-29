<?php

namespace App\Services\Config;

use App\Models\Config\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RoleService
{
    public function create(Request $request): Role
    {
        return Role::forceCreate([
            'name' => Str::of($request->name)->slug('-')->value,
            'uuid' => (string) Str::uuid(),
            'guard_name' => 'web',
        ]);
    }

    public function deletable(Role $role): void
    {
        if ($role->is_default) {
            throw ValidationException::withMessages(['message' => trans('global.could_not_delete_default', ['attribute' => trans('config.role.role')])]);
        }
    }
}

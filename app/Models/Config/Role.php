<?php

namespace App\Models\Config;

use App\Concerns\HasFilter;
use App\Concerns\HasUuid;
use App\Http\Resources\Config\RoleResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFilter, HasUuid, LogsActivity;

    protected $guarded = [];

    protected $primaryKey = 'id';

    protected $table = 'roles';

    public function getIsDefaultAttribute()
    {
        $permission = Arr::getVar('permission');
        $roles = Arr::get($permission, 'roles', []);

        $name = Str::of($this->name)->slug('-')->value;

        if (in_array($name, $roles)) {
            return true;
        }

        return false;
    }

    public static function selectOption()
    {
        return RoleResource::collection(self::when(! \Auth::user()->is_default, function ($q) {
            $q->whereNotIn('name', ['admin']);
        })->get());
    }

    public static function getRolesForPermission()
    {
        return RoleResource::collection(self::whereNotIn('name', ['admin'])->get());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('role')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}

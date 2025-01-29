<?php

namespace App\Models;

use App\Casts\DateTimeCast;
use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscriber extends Model
{
    use HasFactory, HasFilter, HasMeta, HasUuid, LogsActivity;

    protected $fillable = ['email'];

    protected $primaryKey = 'id';

    protected $table = 'subscribers';

    protected $casts = [
        'unsubscribed_at' => DateTimeCast::class,
        'meta' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('subscriber')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}

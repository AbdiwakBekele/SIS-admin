<?php

namespace App\Models;

use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Helpers\SysHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Plan extends Model
{
    use HasFactory, HasFilter, HasMeta, HasUuid, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'plans';

    protected $casts = [
        'meta' => 'array',
        'features' => 'array',
        'tax' => 'array',
        'pricing' => 'array',
    ];

    public function getFeature(string $option)
    {
        return Arr::get($this->features, $option);
    }

    public function getShowInclusionExclusionAttribute()
    {
        return (bool) $this->getMeta('show_inclusion_exclusion', false);
    }

    public function getDescriptionItemsAttribute()
    {
        return explode("\n", $this->description);
    }

    public function getInclusionItemsAttribute()
    {
        return explode("\n", $this->inclusion);
    }

    public function getExclusionItemsAttribute()
    {
        return explode("\n", $this->exclusion);
    }

    public function getPrice($currency, $frequency)
    {
        return Arr::get($this->pricing, 'price.'.$frequency.'.'.$currency, 0);
    }

    public function getPriceWithDuration($currency, $frequency)
    {
        $currencyDetail = SysHelper::getCurrencyDetail($currency);

        return Arr::get($currencyDetail, 'symbol').Arr::get($this->pricing, 'price.'.$frequency.'.'.$currency, 0).' '.trans('plan.frequencies.'.$frequency);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('plan')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}

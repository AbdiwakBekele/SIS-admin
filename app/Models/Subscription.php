<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Concerns\HasDatePeriod;
use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Enums\PlanFrequency;
use App\Helpers\CalHelper;
use App\Helpers\SysHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscription extends Model
{
    use HasDatePeriod, HasFactory, HasFilter, HasMeta, HasUuid, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'subscriptions';

    protected $casts = [
        'frequency' => PlanFrequency::class,
        'start_date' => DateCast::class,
        'expiry_date' => DateCast::class,
        'meta' => 'array',
        'tax_details' => 'array',
        'payment_gateway' => 'array',
        'billing_address' => 'array',
        'failed_logs' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getCustomerAddressAttribute(): string
    {
        return Arr::implode(Arr::notEmpty([
            Arr::get($this->billing_address, 'address.address_line1'),
            Arr::get($this->billing_address, 'address.address_line2'),
            Arr::get($this->billing_address, 'address.city'),
            Arr::get($this->billing_address, 'address.state'),
            Arr::get($this->billing_address, 'address.zipcode'),
            Arr::get($this->billing_address, 'address.country'),
        ]));
    }

    public function getPeriodAttribute(): string
    {
        return CalHelper::getPeriod($this->start_date->value, $this->expiry_date->value);
    }

    public function getAmountDisplayAttribute(): string
    {
        return SysHelper::formatCurrency($this->amount, $this->currency);
    }

    public function getAmountWithoutTaxAttribute(): string
    {
        return SysHelper::formatCurrency($this->amount - $this->tax_amount, $this->currency);
    }

    public function getTaxAmountAttribute(): float
    {
        return Arr::get($this->tax_details, 'amount', 0);
    }

    public function getTaxAmountDisplayAttribute(): string
    {
        return SysHelper::formatCurrency(Arr::get($this->tax_details, 'amount', 0), $this->currency);
    }

    public function getTaxLabelAttribute(): string
    {
        return Arr::get($this->tax_details, 'label');
    }

    public function getTaxRateDisplayAttribute(): string
    {
        return Arr::get($this->tax_details, 'rate').'%';
    }

    public function getTaxTypeDisplayAttribute(): string
    {
        return Arr::get($this->tax_details, 'is_exclusive') ? trans('saas.subscription.props.tax_type_exclusive') : trans('saas.subscription.props.tax_type_inclusive');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('subscription')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}

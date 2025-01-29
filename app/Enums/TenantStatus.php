<?php

namespace App\Enums;

use App\Concerns\HasEnum;
use App\Contracts\HasColor;

enum TenantStatus: string implements HasColor
{
    use HasEnum;

    case PENDING = 'pending';
    case RUNNING = 'running';
    case EXPIRED = 'expired';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';

    public static function translation(): string
    {
        return 'tenant.statuses.';
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'info',
            self::RUNNING => 'success',
            self::EXPIRED => 'danger',
            self::SUSPENDED => 'warning',
            self::TERMINATED => 'dark-danger',
        };
    }

    public function hexColor(): string
    {
        return match ($this) {
            self::PENDING => '#60A5FA',
            self::RUNNING => '#34D399',
            self::EXPIRED => '#EF4444',
            self::SUSPENDED => '#FBBF24',
            self::TERMINATED => '#DC2626',
        };
    }
}

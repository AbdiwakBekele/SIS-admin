<?php

namespace App\Enums;

use App\Concerns\HasEnum;

enum PlanFrequency: string
{
    use HasEnum;

    case MONTHLY = 'monthly';
    case ANNUALLY = 'annually';

    public static function translation(): string
    {
        return 'plan.frequencies.';
    }
}

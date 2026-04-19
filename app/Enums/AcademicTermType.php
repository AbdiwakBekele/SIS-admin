<?php

namespace App\Enums;

use App\Concerns\HasEnum;

enum AcademicTermType: string
{
    use HasEnum;

    case SEMESTER = 'semester';
    case TRIMESTER = 'trimester';
    case QUARTER = 'quarter';
    case YEAR_LONG = 'year_long';

    public static function translation(): string
    {
        return 'tenant.academic_terms.';
    }
}

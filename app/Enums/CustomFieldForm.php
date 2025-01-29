<?php

namespace App\Enums;

use App\Concerns\HasEnum;

enum CustomFieldForm: string
{
    use HasEnum;

    case TODO = 'todo';

    public static function translation(): string
    {
        return 'custom_field.forms.';
    }
}

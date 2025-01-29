<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

trait FormatCodeNumber
{
    public function getCodeNumber(int $number = 0, int $digit = 0, string $format = '', string $string = '', string $date = ''): array
    {
        if (! $date) {
            $date = today()->toDateString();
        }

        $date = strtotime($date);

        $numberFormat = $format;

        $string = $string ?: $format;

        $string = str_replace('%YEAR%', date('Y', $date), $string);
        $string = str_replace('%YEAR_SHORT%', date('y', $date), $string);
        $string = str_replace('%MONTH%', date('F', $date), $string);
        $string = str_replace('%MONTH_SHORT%', date('M', $date), $string);
        $string = str_replace('%MONTH_NUMBER%', date('m', $date), $string);
        $string = str_replace('%MONTH_NUMBER_SHORT%', date('n', $date), $string);
        $string = str_replace('%DAY%', date('d', $date), $string);
        $string = str_replace('%DAY_SHORT%', date('j', $date), $string);
        $string = str_replace('%PERIOD%', config('config.academic.period.shortcode'), $string);

        $details = [
            'code_number' => str_replace('%NUMBER%', str_pad($number, $digit, '0', STR_PAD_LEFT), $string),
            'number_format' => $numberFormat,
            'number' => $number,
        ];

        if (strlen($details['code_number']) > 20) {
            throw ValidationException::withMessages([
                'message' => trans('validation.max.string', ['attribute' => trans('student.registration.props.code_number'), 'max' => 20]),
            ]);
        }

        if (strlen($details['number_format']) > 50) {
            throw ValidationException::withMessages([
                'message' => trans('validation.max.string', ['attribute' => trans('student.registration.props.number_format'), 'max' => 50]),
            ]);
        }

        return $details;
    }
}

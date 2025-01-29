<?php

namespace App\Actions\Config\Module;

use App\Helpers\ListHelper;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class StoreTenantConfig
{
    public static function handle(): array
    {
        $input = request()->validate([
            'trial_period' => 'sometimes|required|integer|min:0|max:365',
            'enable_trial_ends_on_subscription' => 'boolean',
            'subscription_end_alert' => 'sometimes|required|integer|min:1|max:100',
            'unavailable_subdomain' => 'sometimes|nullable',
            'default_timezone' => 'sometimes|required',
            'default_locale' => 'sometimes|required|alpha_dash',
            'default_currency' => 'sometimes|required',
            'default_app_name' => 'sometimes|required',
            'default_author' => 'sometimes|required',
            'default_footer_credit' => 'sometimes|required',
        ], [], [
            'trial_period' => __('tenant.config.props.trial_period'),
            'enable_trial_ends_on_subscription' => __('tenant.config.props.trial_ends_on_subscription'),
            'subscription_end_alert' => __('tenant.config.props.subscription_end_alert'),
            'unavailable_subdomain' => __('tenant.config.props.unavailable_subdomain'),
            'default_timezone' => __('tenant.config.props.default_timezone'),
            'default_locale' => __('tenant.config.props.default_locale'),
            'default_currency' => __('tenant.config.props.default_currency'),
            'default_app_name' => __('tenant.config.props.default_app_name'),
            'default_author' => __('tenant.config.props.default_author'),
            'default_footer_credit' => __('tenant.config.props.default_footer_credit'),
        ]);

        (new self)->validate($input);

        return $input;
    }

    private function validate(array $input = []): void
    {
        $timezone = Arr::get($input, 'default_timezone');
        if ($timezone && ! ListHelper::getListByKey('timezones', 'value', $timezone)) {
            throw ValidationException::withMessages(['default_timezone' => trans('validation.exists', ['attribute' => trans('tenant.config.props.default_timezone')])]);
        }

        $currency = Arr::get($input, 'default_currency');
        if (! ListHelper::getListByKey('currencies', 'name', $currency)) {
            throw ValidationException::withMessages(['default_currency' => trans('validation.exists', ['attribute' => trans('tenant.config.props.default_currency')])]);
        }
    }
}

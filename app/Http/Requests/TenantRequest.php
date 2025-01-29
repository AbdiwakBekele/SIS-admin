<?php

namespace App\Http\Requests;

use App\Concerns\SimpleValidation;
use App\Enums\PlanFrequency;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TenantRequest extends FormRequest
{
    use SimpleValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tenant = $this->route('tenant');

        if ($this->custom_domain) {
            $this->merge(['custom_domain' => 'https://'.$this->custom_domain]);
        }

        $rules = [
            'name' => 'required|min:2|max:100',
            'email' => ['required', 'email', Rule::unique('tenants')->ignore($tenant)],
            'contact_number' => 'required|min:5|max:20',
            'address.address_line1' => 'required|min:2|max:100',
            'address.country' => 'required|min:2|max:100',
            'domain' => ['required', 'min:3', 'max:30', 'alpha_num', 'not_in:'.config('config.tenant.unavailable_subdomain'), Rule::unique('tenants')->ignore($tenant)],
            'custom_domain' => ['nullable', 'url', Rule::unique('tenants')->ignore($tenant)],
            'plan' => 'required|uuid',
            'is_trial' => 'boolean',
            'frequency' => ['required', new Enum(PlanFrequency::class)],
            'currency' => 'required',
        ];

        if ($this->is_trial) {
            $rules['trial_period'] = 'required|integer|min:1|max:100';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            $validator->after(function ($validator) {
                $this->change($validator, 'address.address_line1', 'address_address_line1');
                $this->change($validator, 'address.address_line2', 'address_address_line2');
                $this->change($validator, 'address.city', 'address_city');
                $this->change($validator, 'address.state', 'address_state');
                $this->change($validator, 'address.zipcode', 'address_zipcode');
                $this->change($validator, 'address.country', 'address_country');
            });

            return;
        }

        $validator->after(function ($validator) {
            $tenant = $this->route('tenant');

            $plan = Plan::whereUuid($this->plan)->getOrFail(trans('plan.plan'), 'plan');

            $allowedCurrencies = explode(',', config('config.system.currencies'));

            if (! in_array($this->currency, $allowedCurrencies)) {
                $validator->errors()->add('currency', trans('general.errors.invalid_input'));
            }

            $isTrial = $this->is_trial;
            if ($plan->getFeature('is_free')) {
                $isTrial = 0;
            }

            $this->merge([
                'is_trial' => $isTrial,
                'plan_id' => $plan->id,
                'custom_domain' => Str::of($this->custom_domain)->replace('https://', '')->value,
            ]);
        });
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('tenant.props.name'),
            'email' => __('tenant.props.email'),
            'contact_number' => __('tenant.props.contact_number'),
            'domain' => __('tenant.props.domain'),
            'custom_domain' => __('tenant.props.custom_domain'),
            'plan' => __('plan.plan'),
            'is_trial' => __('global.is_attribute', ['attribute' => __('tenant.props.trial')]),
            'trial_period' => __('tenant.config.props.trial_period'),
            'frequency' => __('plan.props.frequency'),
            'currency' => __('config.system.props.currency'),
            'address.address_line1' => __('contact.props.address.address_line1'),
            'address.country' => __('contact.props.address.country'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}

<?php

namespace App\Http\Requests;

use App\Concerns\SimpleValidation;
use App\Enums\AcademicTermType;
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
            'school_name' => 'required|min:2|max:150',
            'school_logo' => 'nullable|string|max:3500000',
            'academic_term' => ['required', Rule::enum(AcademicTermType::class)],
        ];

        if ($this->boolean('is_trial')) {
            $rules['trial_start_date'] = 'required|date';
            $rules['trial_end_date'] = 'required|date|after_or_equal:trial_start_date';
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

            $isTrial = $this->boolean('is_trial');
            if ($plan->getFeature('is_free')) {
                $isTrial = false;
            }

            if ($this->filled('school_logo') && ! str_starts_with($this->school_logo, 'data:image/')) {
                $validator->errors()->add('school_logo', trans('tenant.errors.invalid_school_logo'));
            }

            if ($this->filled('school_logo') && str_starts_with($this->school_logo, 'data:image/')) {
                $comma = strpos($this->school_logo, ',');
                if ($comma === false) {
                    $validator->errors()->add('school_logo', trans('tenant.errors.invalid_school_logo'));
                } else {
                    $decoded = base64_decode(substr($this->school_logo, $comma + 1), true);
                    if ($decoded === false || strlen($decoded) > 2 * 1024 * 1024) {
                        $validator->errors()->add('school_logo', trans('tenant.errors.invalid_school_logo'));
                    } elseif (@getimagesizefromstring($decoded) === false) {
                        $validator->errors()->add('school_logo', trans('tenant.errors.invalid_school_logo'));
                    }
                }
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
            'trial_start_date' => __('tenant.props.trial_start_date'),
            'trial_end_date' => __('tenant.props.trial_end_date'),
            'school_name' => __('tenant.props.school_name'),
            'school_logo' => __('tenant.props.school_logo'),
            'academic_term' => __('tenant.props.academic_term'),
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

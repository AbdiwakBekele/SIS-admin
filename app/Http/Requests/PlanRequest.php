<?php

namespace App\Http\Requests;

use App\Enums\PlanFrequency;
use App\Helpers\SysHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
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
        $plan = $this->route('plan');

        $rules = [
            'name' => ['required', 'min:2', 'max:100', Rule::unique('plans')->ignore($plan)],
            'code' => ['required', 'min:2', 'max:20', 'alpha', Rule::unique('plans')->ignore($plan)],
            'modules' => 'array',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
            'is_default' => 'boolean',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'has_activation_charge' => 'boolean',
            'allow_using_global_mail_service' => 'boolean',
            'max_team_limit' => 'int|min:1|max:100000',
            'max_user_limit' => 'int|min:1|max:100000',
            'team_wise_limit' => 'boolean',
            'min_student_limit' => 'nullable|int|min:1|max:100000',
            'show_inclusion_exclusion' => 'boolean',
            'description' => 'nullable|min:10|max:1000',
            'inclusion' => 'required_if:show_inclusion_exclusion,1|min:10|max:1000',
            'exclusion' => 'required_if:show_inclusion_exclusion,1|min:10|max:1000',
            'price' => 'array',
            'price.*.amount.value' => 'numeric|min:0|max:1000000',
            'activation_charge' => 'array',
            'activation_charge.*.amount.value' => 'numeric|min:0|max:1000000',
        ];

        if ($this->boolean('team_wise_limit')) {
            $rules['max_student_per_team_limit'] = 'required|min:1|max:100000';
            $rules['max_employee_per_team_limit'] = 'required|min:1|max:100000';
        } else {
            $rules['min_student_limit'] = 'required|min:1|max:100000|lte:max_student_limit';
            $rules['max_student_limit'] = 'required|min:1|max:100000|gte:min_student_limit';
            $rules['max_employee_limit'] = 'required|min:1|max:100000';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('plan.uuid');

            $newPrice = [];
            foreach (SysHelper::getAvailableCurrencies() as $currencyName) {
                foreach (PlanFrequency::getKeys() as $frequency) {
                    $price = Arr::first($this->price, function ($item) use ($currencyName, $frequency) {
                        return Arr::get($item, 'currency.name') == $currencyName && Arr::get($item, 'frequency.value') == $frequency;
                    });

                    $amount = $this->is_free ? 0 : Arr::get($price, 'amount.value', 0);

                    $newPrice[$frequency][$currencyName] = $amount;
                }
            }

            $newActivationCharge = [];
            foreach (SysHelper::getAvailableCurrencies() as $currencyName) {
                $activationCharge = Arr::first($this->activation_charge, function ($item) use ($currencyName) {
                    return Arr::get($item, 'currency.name') == $currencyName;
                });

                $amount = $this->has_activation_charge ? Arr::get($activationCharge, 'amount.value', 0) : 0;

                $newActivationCharge[$currencyName] = $amount;
            }

            $this->merge([
                'price' => $newPrice,
                'activation_charge' => $newActivationCharge,
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
            'name' => __('plan.props.name'),
            'code' => __('plan.props.code'),
            'is_active' => __('global.is_attribute', ['attribute' => __('plan.props.active')]),
            'is_free' => __('global.is_attribute', ['attribute' => __('plan.props.free')]),
            'is_default' => __('global.is_attribute', ['attribute' => __('plan.props.default')]),
            'is_featured' => __('global.is_attribute', ['attribute' => __('plan.props.featured')]),
            'is_visible' => __('global.is_attribute', ['attribute' => __('plan.props.visible')]),
            'has_activation_charge' => __('global.has_attribute', ['attribute' => __('plan.props.activation_charge')]),
            'allow_using_global_mail_service' => __('plan.props.allow_using_global_mail_service'),
            'max_student_limit' => __('plan.props.max_student_limit'),
            'min_student_limit' => __('plan.props.min_student_limit'),
            'max_employee_limit' => __('plan.props.max_employee_limit'),
            'max_team_limit' => __('plan.props.max_team_limit'),
            'team_wise_limit' => __('plan.props.team_wise_limit'),
            'max_student_per_team_limit' => __('plan.props.max_student_per_team_limit'),
            'max_employee_per_team_limit' => __('plan.props.max_employee_per_team_limit'),
            'max_user_limit' => __('plan.props.max_user_limit'),
            'modules' => __('plan.props.modules'),
            'description' => __('plan.props.description'),
            'show_inclusion_exclusion' => __('plan.props.show_inclusion_exclusion'),
            'inclusion' => __('plan.props.inclusion'),
            'exclusion' => __('plan.props.exclusion'),
            'price.*.amount.value' => __('plan.props.amount'),
            'activation_charge.*.amount.value' => __('plan.props.amount'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'description.required_unless' => __('validation.required', ['attribute' => __('plan.props.description')]),
            'inclusion.required_if' => __('validation.required', ['attribute' => __('plan.props.inclusion')]),
            'exclusion.required_if' => __('validation.required', ['attribute' => __('plan.props.exclusion')]),
        ];
    }
}

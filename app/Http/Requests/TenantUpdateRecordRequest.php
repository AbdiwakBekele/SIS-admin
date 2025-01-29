<?php

namespace App\Http\Requests;

use App\Concerns\SimpleValidation;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;

class TenantUpdateRecordRequest extends FormRequest
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
        $rules = [
            'plan' => 'required|uuid',
            'expiry_date' => 'required|date_format:Y-m-d',
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $plan = Plan::whereUuid($this->plan)->getOrFail(trans('plan.plan'), 'plan');

            $this->merge([
                'plan_id' => $plan->id,
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
            'plan' => __('plan.plan'),
            'expiry_date' => __('tenant.props.expiry_date'),
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

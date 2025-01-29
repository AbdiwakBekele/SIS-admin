<?php

namespace App\Http\Requests;

use App\Concerns\SimpleValidation;
use App\Enums\PlanFrequency;
use App\Helpers\CalHelper;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SubscriptionRequest extends FormRequest
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
        return [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|min:2|max:100',
            'address.address_line1' => 'required|min:2|max:100',
            'address.country' => 'required|min:2|max:100',
            'amount' => 'required|numeric|min:0|max:10000000',
            'start_date' => 'required|date|before:expiry_date',
            'expiry_date' => 'required|date',
            'tenant' => 'required|uuid',
            'plan' => 'required|uuid',
            'frequency' => ['required', new Enum(PlanFrequency::class)],
            'currency' => 'required',
        ];
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
            $uuid = $this->route('subscription.uuid');

            $plan = Plan::whereUuid($this->plan)->getOrFail(trans('plan.plan'), 'plan');
            $tenant = Tenant::whereUuid($this->tenant)->getOrFail(trans('tenant.tenant'), 'tenant');

            if ($this->frequency == 'annually' && ! $plan->getMeta('enable_annual_subscription')) {
                $validator->errors()->add('frequency', trans('subscription.frequency_not_allowed', ['attribute' => __('plan.frequencies.annually')]));
            }

            if ($this->frequency == 'monthly' && ! $plan->getMeta('enable_monthly_subscription')) {
                $validator->errors()->add('frequency', trans('subscription.frequency_not_allowed', ['attribute' => __('plan.frequencies.monthly')]));
            }

            $overlappingSubscription = Subscription::query()
                ->whereTenantId($tenant->id)
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->betweenPeriod($this->start_date, $this->expiry_date, 'start_date', 'expiry_date')
                ->count();

            // if ($overlappingSubscription) {
            //     $validator->errors()->add('message', trans('subscription.range_exists', ['start' => CalHelper::showDate($this->start_date), 'end' => CalHelper::showDate($this->expiry_date)]));
            // }

            $allowedCurrencies = explode(',', config('config.system.currencies'));

            if (! in_array($this->currency, $allowedCurrencies)) {
                $validator->errors()->add('currency', trans('general.errors.invalid_input'));
            }

            $this->merge([
                'tenant_id' => $tenant->id,
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
            'name' => __('subscription.props.name'),
            'email' => __('subscription.props.email'),
            'address.address_line1' => __('contact.props.address.address_line1'),
            'address.country' => __('contact.props.address.country'),
            'amount' => __('subscription.props.amount'),
            'start_date' => __('subscription.props.start_date'),
            'expiry_date' => __('subscription.props.expiry_date'),
            'plan' => __('plan.plan'),
            'frequency' => __('plan.props.frequency'),
            'currency' => __('config.system.props.currency'),
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

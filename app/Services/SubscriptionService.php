<?php

namespace App\Services;

use App\Enums\PlanFrequency;
use App\Helpers\ListHelper;
use App\Http\Resources\PlanResource;
use App\Http\Resources\TenantResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Support\FormatCodeNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SubscriptionService
{
    use FormatCodeNumber;

    private function codeNumber()
    {
        $numberPrefix = config('config.subscription.invoice_number_prefix');
        $numberSuffix = config('config.subscription.invoice_number_suffix');
        $digit = config('config.subscription.invoice_number_digit', 0);

        $numberFormat = $numberPrefix.'%NUMBER%'.$numberSuffix;
        $codeNumber = (int) Subscription::query()
            ->whereStatus(1)
            ->whereNumberFormat($numberFormat)
            ->max('number');

        $codeNumber += 1;

        return $this->getCodeNumber(number: $codeNumber, digit: $digit, format: $numberFormat);
    }

    private function validateCodeNumber(Request $request, ?string $uuid = null): array
    {
        $duplicateCodeNumber = Subscription::query()
            ->whereCodeNumber($request->code_number)
            ->when($uuid, function ($q, $uuid) {
                $q->where('uuid', '!=', $uuid);
            })->count();

        if ($duplicateCodeNumber) {
            throw ValidationException::withMessages(['code_number' => trans('global.duplicate', ['attribute' => trans('subscription.props.code_number')])]);
        }

        $codeNumberDetail = $this->codeNumber();

        return $request->code_number == Arr::get($codeNumberDetail, 'code_number') ? $codeNumberDetail : [];
    }

    public function preRequisite(Request $request): array
    {
        $codeNumber = Arr::get($this->codeNumber(), 'code_number');

        $currencies = ListHelper::getList('currencies', 'name');
        $frequencies = PlanFrequency::getOptions();

        $plans = PlanResource::collection(Plan::query()
            ->get());
        $tenants = TenantResource::collection(Tenant::query()
            ->get());

        return compact('currencies', 'frequencies', 'codeNumber', 'plans', 'tenants');
    }

    public function create(Request $request): Subscription
    {
        \DB::beginTransaction();

        $subscription = Subscription::forceCreate($this->formatParams($request));

        \DB::commit();

        return $subscription;
    }

    private function formatParams(Request $request, ?Subscription $subscription = null): array
    {
        $codeNumberDetail = $this->validateCodeNumber($request, $subscription?->uuid);

        $formatted = [
            'number_format' => Arr::get($codeNumberDetail, 'number_format'),
            'number' => Arr::get($codeNumberDetail, 'number'),
            'code_number' => $request->code_number,
            'tenant_id' => $request->tenant_id,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'currency' => $request->currency,
            'billing_address' => [
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
            ],
            'status' => 1,
        ];

        $meta = $subscription ? $subscription->meta : [];

        if (! $subscription) {
            $meta['reference_number'] = Str::random(16);
        }

        $meta['company'] = [
            'tax_id_label' => config('config.subscription.tax_id_label'),
            'tax_id_number' => config('config.subscription.tax_id_number'),
            'name' => config('config.subscription.company_name'),
            'address' => config('config.subscription.company_address'),
            'email' => config('config.subscription.company_email'),
            'phone' => config('config.subscription.company_phone'),
            'tnc' => config('config.subscription.invoice_terms'),
        ];
        $meta['activation_charge'] = $request->activation_charge ?? 0;

        $formatted['meta'] = $meta;

        return $formatted;
    }

    private function isEditable(Subscription $subscription): void
    {
        if ($subscription->getMeta('is_online')) {
            throw ValidationException::withMessages(['message' => trans('subscription.could_not_perform_if_is_online')]);
        }

        $ensureLastSubscription = Subscription::query()
            ->whereTenantId($subscription->tenant_id)
            ->where('start_date', '>', $subscription->start_date->value)
            ->doesntExist();

        if (! $ensureLastSubscription) {
            throw ValidationException::withMessages(['message' => trans('subscription.could_not_perform_if_not_last_subscription')]);
        }
    }

    public function update(Request $request, Subscription $subscription): void
    {
        $this->isEditable($subscription);

        \DB::beginTransaction();

        $subscription->forceFill($this->formatParams($request, $subscription))->save();

        \DB::commit();
    }

    public function deletable(Subscription $subscription): void
    {
        $this->isEditable($subscription);
    }
}

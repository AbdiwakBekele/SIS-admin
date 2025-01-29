<?php

namespace App\Services;

use App\Enums\PlanFrequency;
use App\Enums\TenantStatus;
use App\Helpers\ListHelper;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenantService
{
    public function preRequisite(Request $request): array
    {
        $currencies = ListHelper::getList('currencies', 'name');
        $frequencies = PlanFrequency::getOptions();

        $plans = PlanResource::collection(Plan::query()
            ->get());

        return compact('currencies', 'frequencies', 'plans');
    }

    public function create(Request $request): Tenant
    {
        \DB::beginTransaction();

        $tenant = Tenant::forceCreate($this->formatParams($request));

        \DB::commit();

        return $tenant;
    }

    private function formatParams(Request $request, ?Tenant $tenant = null): array
    {
        $meta = $tenant?->meta ?? [];

        $formatted = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => [
                'address_line1' => $request->input('address.address_line1'),
                'address_line2' => $request->input('address.address_line2'),
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'zipcode' => $request->input('address.zipcode'),
                'country' => $request->input('address.country'),
            ],
        ];

        if (! $tenant || ($tenant && $tenant->status == TenantStatus::PENDING->value)) {
            $formatted['plan_id'] = $request->plan_id;
            $formatted['domain'] = $request->domain;
            $formatted['custom_domain'] = $request->custom_domain;
            $formatted['is_trial'] = $request->boolean('is_trial');

            $meta['currency'] = $request->currency;
            $meta['frequency'] = $request->frequency;
            $meta['trial_period'] = $request->is_trial ? (int) $request->trial_period : 0;
            $meta['trial_ends_at'] = $request->is_trial ? today()->addDays((int) $request->trial_period)->toDateString() : null;
        }

        if (! $tenant) {
            $formatted['status'] = TenantStatus::PENDING->value;

            $meta['activation_token'] = (string) Str::uuid();
        }

        $formatted['meta'] = $meta;

        return $formatted;
    }

    public function update(Request $request, Tenant $tenant): void
    {
        \DB::beginTransaction();

        $tenant->forceFill($this->formatParams($request, $tenant))->save();

        \DB::commit();
    }

    public function deletable(Tenant $tenant): void
    {
        $subscriptionExists = Subscription::whereTenantId($tenant->id)->exists();

        if ($subscriptionExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('tenant.tenant'), 'dependency' => trans('subscription.subscription')])]);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlanService
{
    public function preRequisite(Request $request): array
    {
        $modules = [];

        return compact('modules');
    }

    public function create(Request $request): Plan
    {
        \DB::beginTransaction();

        $plan = Plan::forceCreate($this->formatParams($request));

        \DB::commit();

        return $plan;
    }

    private function formatParams(Request $request, ?Plan $plan = null): array
    {
        $formatted = [
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'inclusion' => $request->inclusion,
            'exclusion' => $request->exclusion,
            'features' => [
                'modules' => $request->modules,
                'is_active' => $request->boolean('is_active'),
                'is_free' => $request->boolean('is_free'),
                'is_default' => $request->boolean('is_default'),
                'is_featured' => $request->boolean('is_featured'),
                'is_visible' => $request->boolean('is_visible'),
                'has_activation_charge' => $request->boolean('has_activation_charge'),
                'allow_using_global_mail_service' => $request->boolean('allow_using_global_mail_service'),
                'max_team_limit' => (int) $request->max_team_limit,
                'max_user_limit' => (int) $request->max_user_limit,
                'team_wise_limit' => $request->team_wise_limit,
                'max_student_limit' => ! $request->team_wise_limit ? (int) $request->max_student_limit : null,
                'max_employee_limit' => ! $request->team_wise_limit ? (int) $request->max_employee_limit : null,
                'max_student_per_team_limit' => $request->team_wise_limit ? (int) $request->max_student_per_team_limit : null,
                'max_employee_per_team_limit' => $request->team_wise_limit ? (int) $request->max_employee_per_team_limit : null,
            ],
            'tax' => [
                'is_enabled' => $request->boolean('is_free') ? 0 : $request->boolean('enable_tax'),
                'label' => $request->tax_label,
                'rate' => $request->tax_rate,
                'is_exclusive' => $request->boolean('tax_type_exclusive'),
            ],
            'pricing' => [
                'price' => $request->price,
                'activation_charge' => $request->activation_charge,
            ],
        ];

        $meta = $plan?->meta ?? [];

        if (! $plan) {
        }

        $meta['show_inclusion_exclusion'] = $request->boolean('show_inclusion_exclusion');
        $meta['enable_monthly_subscription'] = $request->boolean('enable_monthly_subscription');
        $meta['enable_annual_subscription'] = $request->boolean('enable_annual_subscription');
        $formatted['meta'] = $meta;

        return $formatted;
    }

    public function update(Request $request, Plan $plan): void
    {
        \DB::beginTransaction();

        $plan->forceFill($this->formatParams($request, $plan))->save();

        \DB::commit();
    }

    public function deletable(Plan $plan): void
    {
        $tenantExists = Tenant::wherePlanId($plan->id)->exists();

        if ($tenantExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('plan.plan'), 'dependency' => trans('tenant.tenant')])]);
        }

        $subscriptionExists = Subscription::wherePlanId($plan->id)->exists();

        if ($subscriptionExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('plan.plan'), 'dependency' => trans('subscription.subscription')])]);
        }
    }
}

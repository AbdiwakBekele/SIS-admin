<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PlanActionService
{
    public function reorder(Request $request): void
    {
        $plans = $request->plans ?? [];

        $allPlans = Plan::query()
            ->get();

        foreach ($plans as $index => $planItem) {
            $plan = $allPlans->firstWhere('uuid', Arr::get($planItem, 'uuid'));

            if (! $plan) {
                continue;
            }

            $plan->position = $index + 1;
            $plan->save();
        }
    }
}

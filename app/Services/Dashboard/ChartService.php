<?php

namespace App\Services\Dashboard;

use App\Enums\TenantStatus;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ChartService
{
    public function getData(Request $request): array
    {
        $statuses = collect(TenantStatus::getOptions())->sortBy('label')->all();

        $tenants = Tenant::selectRaw('status, count(*) as count')->groupBy('status')->get();

        $labels = [];
        $data = [];
        $color = [];
        foreach ($statuses as $status) {
            $label = Arr::get($status, 'label');
            $value = Arr::get($status, 'value');

            array_push($labels, $label);
            array_push($data, $tenants->firstWhere('status.value', $value)?->count ?? 0);
            array_push($color, TenantStatus::tryFrom($value)->hexColor());
        }

        $statusWiseData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $color,
                ],
            ],
        ];

        $plans = Plan::orderBy('name', 'asc')->get();

        $tenants = Tenant::selectRaw('plan_id, count(*) as count')->groupBy('plan_id')->get();

        $colors = ['#37A2EB', '#FF6384', '#FEA03F', '#FFCD56', '#4BC0C0', '#77FF34', '#63FF16', '#777777', '#00B3B3', '#37A2EB'];
        $labels = [];
        $data = [];
        $color = [];
        foreach ($plans as $plan) {
            array_push($labels, $plan->name);
            array_push($data, $tenants->where('plan_id', $plan->id)?->count() ?? 0);
            array_push($color, array_shift($colors));
        }

        $planWiseData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $color,
                ],
            ],
        ];

        return compact('statusWiseData', 'planWiseData');
    }
}

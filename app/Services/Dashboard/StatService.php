<?php

namespace App\Services\Dashboard;

use App\Models\Query;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class StatService
{
    public function getData(Request $request)
    {
        $date = today()->toDateString();

        $tenantStat = Tenant::query()
            ->selectRaw('count(*) as total_tenant')
            ->selectRaw('count(case when created_at >= '."'".today()->startOfMonth()->toDateTimeString()."'".' then 1 end) as total_tenant_this_month')
            ->first();

        $subscriptionStat = Subscription::query()
            ->whereStatus(1)
            ->selectRaw('count(*) as total_subscription')
            ->selectRaw('count(case when created_at >= '."'".today()->startOfMonth()->toDateTimeString()."'".' then 1 end) as total_subscription_this_month')
            ->first();

        $queryStat = Query::query()
            ->selectRaw('count(*) as total_query')
            ->selectRaw('count(case when created_at >= '."'".today()->startOfMonth()->toDateTimeString()."'".' then 1 end) as total_query_this_month')
            ->first();

        $stats = [
            [
                'title' => trans('dashboard.total_tenant'),
                'count' => $tenantStat->total_tenant,
                'icon' => 'fas fa-users',
                'color' => 'bg-success',
                'secondary_title' => trans('global.this_duration', ['attribute' => trans('list.durations.month')]),
                'secondary_count' => $tenantStat->total_tenant_this_month,
            ],
            [
                'title' => trans('dashboard.total_subscription'),
                'count' => $subscriptionStat->total_subscription,
                'icon' => 'fas fa-sticky-note',
                'color' => 'bg-primary dark:bg-gray-700',
                'secondary_title' => trans('global.this_duration', ['attribute' => trans('list.durations.month')]),
                'secondary_count' => $subscriptionStat->total_subscription_this_month,
            ],
            [
                'title' => trans('dashboard.total_query'),
                'count' => $queryStat->total_query,
                'icon' => 'fas fa-envelope',
                'color' => 'bg-info',
                'secondary_title' => trans('global.this_duration', ['attribute' => trans('list.durations.month')]),
                'secondary_count' => $queryStat->total_query_this_month,
            ],
        ];

        return compact('stats');
    }
}

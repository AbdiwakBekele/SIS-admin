<?php

namespace App\Services\Dashboard;

use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\TenantResource;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class RecordService
{
    public function getData(Request $request): array
    {
        $tenants = TenantResource::collection(Tenant::query()
            ->with('plan')
            ->latest()
            ->take(5)
            ->get());

        $subscriptions = SubscriptionResource::collection(Subscription::query()
            ->with('tenant', 'plan')
            ->whereStatus(1)
            ->latest()
            ->take(5)
            ->get());

        return compact('tenants', 'subscriptions');
    }
}

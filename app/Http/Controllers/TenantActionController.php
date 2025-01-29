<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantUpdateRecordRequest;
use App\Models\Tenant;
use App\Services\TenantActionService;
use Illuminate\Http\Request;

class TenantActionController extends Controller
{
    public function activated(Request $request, Tenant $tenant, TenantActionService $service)
    {
        return response()->ok($service->activated($request, $tenant));
    }

    public function updateRecord(TenantUpdateRecordRequest $request, Tenant $tenant, TenantActionService $service)
    {
        $this->authorize('update', $tenant);

        $service->updateRecord($request, $tenant);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('tenant.tenant')]),
        ]);
    }
}

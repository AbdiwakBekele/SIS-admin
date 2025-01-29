<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantListService;
use App\Services\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['update', 'destroy']);
    }

    public function preRequisite(Request $request, TenantService $service)
    {
        $this->authorize('preRequisite', Tenant::class);

        return response()->ok($service->preRequisite($request));
    }

    public function index(Request $request, TenantListService $service)
    {
        $this->authorize('viewAny', Tenant::class);

        return $service->paginate($request);
    }

    public function store(TenantRequest $request, TenantService $service)
    {
        $this->authorize('create', Tenant::class);

        $tenant = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('tenant.tenant')]),
            'tenant' => TenantResource::make($tenant),
        ]);
    }

    public function show(Tenant $tenant, TenantService $service)
    {
        $this->authorize('view', $tenant);

        $tenant->load([
            'plan',
            'subscriptions' => function ($q) {
                $q->whereStatus(1);
            },
            'subscriptions.plan',
        ]);

        return TenantResource::make($tenant);
    }

    public function update(TenantRequest $request, Tenant $tenant, TenantService $service)
    {
        $this->authorize('update', $tenant);

        $service->update($request, $tenant);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('tenant.tenant')]),
        ]);
    }

    public function destroy(Tenant $tenant, TenantService $service)
    {
        $this->authorize('delete', $tenant);

        $service->deletable($tenant);

        $tenant->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('tenant.tenant')]),
        ]);
    }
}

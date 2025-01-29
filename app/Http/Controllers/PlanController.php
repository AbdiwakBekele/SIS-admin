<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PlanListService;
use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function preRequisite(Request $request, PlanService $service)
    {
        return $service->preRequisite($request);
    }

    public function index(Request $request, PlanListService $service)
    {
        $this->authorize('viewAny', Plan::class);

        return $service->paginate($request);
    }

    public function store(PlanRequest $request, PlanService $service)
    {
        $this->authorize('create', Plan::class);

        $plan = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('plan.plan')]),
            'plan' => PlanResource::make($plan),
        ]);
    }

    public function show(Plan $plan, PlanService $service)
    {
        $this->authorize('view', $plan);

        return PlanResource::make($plan);
    }

    public function update(PlanRequest $request, Plan $plan, PlanService $service)
    {
        $this->authorize('update', $plan);

        $service->update($request, $plan);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('plan.plan')]),
        ]);
    }

    public function destroy(Plan $plan, PlanService $service)
    {
        $this->authorize('delete', $plan);

        $service->deletable($plan);

        $plan->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('plan.plan')]),
        ]);
    }
}

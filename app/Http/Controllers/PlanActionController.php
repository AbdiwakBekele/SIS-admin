<?php

namespace App\Http\Controllers;

use App\Services\PlanActionService;
use Illuminate\Http\Request;

class PlanActionController extends Controller
{
    public function reorder(Request $request, PlanActionService $service)
    {
        $menu = $service->reorder($request);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('plan.plan')]),
        ]);
    }
}

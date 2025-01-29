<?php

namespace App\Http\Controllers;

use App\Services\PlanListService;
use Illuminate\Http\Request;

class PlanExportController extends Controller
{
    public function __invoke(Request $request, PlanListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}

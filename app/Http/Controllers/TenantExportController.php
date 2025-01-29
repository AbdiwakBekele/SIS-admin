<?php

namespace App\Http\Controllers;

use App\Services\TenantListService;
use Illuminate\Http\Request;

class TenantExportController extends Controller
{
    public function __invoke(Request $request, TenantListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}

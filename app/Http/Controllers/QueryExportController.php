<?php

namespace App\Http\Controllers;

use App\Services\QueryListService;
use Illuminate\Http\Request;

class QueryExportController extends Controller
{
    public function __invoke(Request $request, QueryListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}

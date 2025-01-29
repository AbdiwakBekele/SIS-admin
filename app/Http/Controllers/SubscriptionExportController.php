<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionListService;
use Illuminate\Http\Request;

class SubscriptionExportController extends Controller
{
    public function __invoke(Request $request, SubscriptionListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}

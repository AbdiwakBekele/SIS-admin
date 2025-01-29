<?php

namespace App\Http\Controllers;

use App\Services\SubscriberListService;
use Illuminate\Http\Request;

class SubscriberExportController extends Controller
{
    public function __invoke(Request $request, SubscriberListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}

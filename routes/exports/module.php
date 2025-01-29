<?php

use App\Http\Controllers\PlanExportController;
use App\Http\Controllers\QueryExportController;
use App\Http\Controllers\SubscriberExportController;
use App\Http\Controllers\SubscriptionExportController;
use App\Http\Controllers\TenantExportController;
use Illuminate\Support\Facades\Route;

Route::get('plans/export', PlanExportController::class)->middleware('permission:plan:read');

Route::get('tenants/export', TenantExportController::class)->middleware('permission:tenant:read');

Route::get('subscriptions/export', SubscriptionExportController::class)->middleware('permission:subscription:read');

Route::get('subscribers/export', SubscriberExportController::class)->middleware('permission:subscriber:read');

Route::get('queries/export', QueryExportController::class)->middleware('permission:query:read');

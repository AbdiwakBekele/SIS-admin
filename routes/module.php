<?php

use App\Http\Controllers\PlanActionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TenantActionController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('plans/pre-requisite', [PlanController::class, 'preRequisite'])->name('plan.preRequisite');

Route::post('plans/reorder', [PlanActionController::class, 'reorder'])->name('plans.reorder');

Route::apiResource('plans', PlanController::class);

Route::get('tenants/pre-requisite', [TenantController::class, 'preRequisite'])->name('tenant.preRequisite');
Route::post('tenants/{tenant}/record', [TenantActionController::class, 'updateRecord'])->name('tenant.record');
Route::apiResource('tenants', TenantController::class);

Route::get('subscriptions/pre-requisite', [SubscriptionController::class, 'preRequisite'])->name('subscription.preRequisite');
Route::apiResource('subscriptions', SubscriptionController::class);

Route::apiResource('subscribers', SubscriberController::class);

Route::apiResource('queries', QueryController::class)->only(['index', 'show', 'destroy']);

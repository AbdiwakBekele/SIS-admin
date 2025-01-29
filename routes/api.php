<?php

use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\GuestMediaController;
use App\Http\Controllers\TenantActionController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Global Routes
Route::get('ok', function () {
    return response()->json([]);
})->name('ok');

Route::withoutMiddleware(VerifyCsrfToken::class)->group(function () {
    Route::post('tenants/{tenant}/activated', [TenantActionController::class, 'activated'])->name('tenants.activated');
});

Route::get('config/pre-requisite', [ConfigController::class, 'preRequisite'])->name('config.preRequisite');
Route::get('config', [ConfigController::class, 'index'])
    ->middleware('optional.auth:sanctum')
    ->name('config');

Route::prefix('app/guest')->group(function () {
    Route::resource('medias', GuestMediaController::class)->only(['store', 'destroy']);
});

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => trans('general.errors.api_not_found')], 404);
});

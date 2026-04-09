<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Config\MailTemplateController;
use App\Http\Controllers\DownloadFormatController;
use App\Http\Controllers\SignedMediaController;
use App\Http\Controllers\SubscriptionActionController;
use App\Http\Controllers\TenantLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/app/config/mail-template/{mail_template}', [MailTemplateController::class, 'detail'])
    ->name('config.mail-template.detail')
    ->middleware('permission:config:store');

Route::get('/media/{media}/{conversion?}', SignedMediaController::class)->name('media');

Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback']);

Route::get('/subscriptions/{subscription}/print', [SubscriptionActionController::class, 'print'])
    ->middleware(['web', 'auth', 'two.factor.security', 'screen.lock', 'under.maintenance', 'user.config', 'permission:subscription:read'])
    ->name('subscriptions.print');

Route::get('login-as-tenant/{tenant}', TenantLoginController::class)
    ->middleware(['web', 'auth', 'user.config', 'permission:tenant:login'])
    ->name('login-as-tenant');

Route::redirect('/log', 'log-viewer', 301);

Route::get('/download/formats', DownloadFormatController::class)->name('download.formats');

Route::get('/my-ip', function () {
    return [
        'direct_ip' => request()->ip(),
        'forwarded_for' => request()->header('X-Forwarded-For'),
        'real_ip' => request()->header('X-Real-IP'),
        'all_ips' => request()->ips(),
    ];
});

Route::redirect('/', '/app/login');

// app route
Route::redirect('/app', '/app/login');

Route::get('/app/login', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('app');

Route::get('/app/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('app.dashboard');

// Fallback route
Route::fallback(function () {
    abort(404);
});

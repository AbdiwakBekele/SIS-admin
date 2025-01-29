<?php

namespace App\Http\Controllers;

use App\Enums\TenantStatus;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TenantLoginController extends Controller
{
    public function __invoke(Request $request, string $tenant)
    {
        $tenant = Tenant::query()
            ->where('uuid', $tenant)
            ->firstOrFail();

        if (in_array($tenant->status, [TenantStatus::PENDING])) {
            abort(398, 'Invalid account.');
        }

        $domainName = Arr::get(parse_url(config('app.url')), 'host');

        $subdomain = 'https://'.$tenant->domain.'.'.$domainName;

        $token = (string) Str::uuid();
        $tenant->setMeta([
            'login_token' => $token,
            'login_token_expires_at' => now()->addMinutes(1)->toDateTimeString(),
        ]);
        $tenant->save();

        if ($tenant->custom_domain) {
            $url = Str::startsWith('http', $tenant->custom_domain) ? $tenant->custom_domain : 'https://'.$tenant->custom_domain;

            return redirect()->away($url.'/login-as-link?token='.$token);
        }

        return redirect()->away($subdomain.'/login-as-link?token='.$token);
    }
}

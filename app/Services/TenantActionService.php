<?php

namespace App\Services;

use App\Enums\TenantStatus;
use App\Models\Tenant;
use App\Models\TenantRecord;
use App\Models\User;
use App\Notifications\AccountActivated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TenantActionService
{
    public function activated(Request $request, Tenant $tenant): void
    {
        $privateKey = $request->header('X-Landlord-Key');

        if (empty($privateKey)) {
            throw ValidationException::withMessages(['message' => __('user.errors.permission_denied')]);
        }

        if ($privateKey != config('app.landlord_private_key')) {
            throw ValidationException::withMessages(['message' => __('user.errors.permission_denied')]);
        }

        $activatedAt = $tenant->getMeta('activated_at');

        if (! $activatedAt) {
            return;
        }

        if ($tenant->getMeta('activated_mail_sent_at')) {
            return;
        }

        $activatedAt = Carbon::parse($activatedAt);

        if (abs(Carbon::now()->diffInHours($activatedAt)) > 1) {
            return;
        }

        (new User)->forceFill([
            'email' => $tenant->email,
        ])->notify(new AccountActivated($tenant));

        $tenant->setMeta([
            'activated_mail_sent_at' => now()->toDateTimeString(),
        ]);
        $tenant->save();
    }

    public function updateRecord(Request $request, Tenant $tenant): void
    {
        if ($tenant->status == TenantStatus::PENDING) {
            throw ValidationException::withMessages(['message' => trans('tenant.could_not_modify_pending_tenant')]);
        }

        \DB::beginTransaction();

        $tenant->plan_id = $request->plan_id;
        $tenant->expiry_date = $request->expiry_date;
        $tenant->save();

        TenantRecord::forceCreate([
            'tenant_id' => $tenant->id,
            'plan_id' => $tenant->plan_id,
            'status' => $tenant->status,
            'expiry_date' => $tenant->expiry_date->value,
        ]);

        \DB::commit();
    }
}

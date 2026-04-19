<?php

namespace App\Services;

use App\Enums\AcademicTermType;
use App\Enums\PlanFrequency;
use App\Enums\TenantStatus;
use App\Helpers\ListHelper;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenantService
{
    public function preRequisite(Request $request): array
    {
        $currencies = ListHelper::getList('currencies', 'name');
        $frequencies = PlanFrequency::getOptions();

        $plans = PlanResource::collection(Plan::query()
            ->get());

        $academicTerms = AcademicTermType::getOptions();

        return compact('currencies', 'frequencies', 'plans', 'academicTerms');
    }

    public function create(Request $request): Tenant
    {
        \DB::beginTransaction();

        $tenant = Tenant::forceCreate($this->formatParams($request));

        \DB::commit();

        return $tenant;
    }

    private function formatParams(Request $request, ?Tenant $tenant = null): array
    {
        $meta = $tenant?->meta ?? [];

        $formatted = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => [
                'address_line1' => $request->input('address.address_line1'),
                'address_line2' => $request->input('address.address_line2'),
                'city' => $request->input('address.city'),
                'state' => $request->input('address.state'),
                'zipcode' => $request->input('address.zipcode'),
                'country' => $request->input('address.country'),
            ],
        ];

        if (! $tenant || ($tenant && $tenant->status == TenantStatus::PENDING->value)) {
            $formatted['plan_id'] = $request->plan_id;
            $formatted['domain'] = $request->domain;
            $formatted['custom_domain'] = $request->custom_domain;
            $formatted['is_trial'] = $request->boolean('is_trial');

            $meta['currency'] = $request->currency;
            $meta['frequency'] = $request->frequency;

            if ($request->boolean('is_trial') && $request->filled('trial_start_date') && $request->filled('trial_end_date')) {
                $start = Carbon::parse($request->trial_start_date)->startOfDay();
                $end = Carbon::parse($request->trial_end_date)->startOfDay();
                $meta['trial_starts_at'] = $start->toDateString();
                $meta['trial_ends_at'] = $end->toDateString();
                $meta['trial_period'] = (int) $start->diffInDays($end) + 1;
            } else {
                $meta['trial_starts_at'] = null;
                $meta['trial_ends_at'] = null;
                $meta['trial_period'] = 0;
            }
        }

        $meta['school_name'] = $request->input('school_name');
        $meta['academic_term'] = $request->input('academic_term');

        if ($request->filled('school_logo') && str_starts_with($request->school_logo, 'data:image/')) {
            $meta['school_logo'] = $this->storeSchoolLogoFromDataUrl($request->school_logo);
        } elseif ($tenant) {
            $meta['school_logo'] = $tenant->getMeta('school_logo');
        }

        if (! $tenant) {
            $formatted['status'] = TenantStatus::PENDING->value;

            $meta['activation_token'] = (string) Str::uuid();
        }

        $formatted['meta'] = $meta;

        return $formatted;
    }

    private function storeSchoolLogoFromDataUrl(string $dataUrl): string
    {
        if (! preg_match('#^data:image/(jpeg|jpg|png|gif|webp);base64,#i', $dataUrl, $m)) {
            throw ValidationException::withMessages([
                'school_logo' => [trans('tenant.errors.invalid_school_logo')],
            ]);
        }

        $binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1), true);
        if ($binary === false || strlen($binary) > 2 * 1024 * 1024) {
            throw ValidationException::withMessages([
                'school_logo' => [trans('tenant.errors.invalid_school_logo')],
            ]);
        }

        if (@getimagesizefromstring($binary) === false) {
            throw ValidationException::withMessages([
                'school_logo' => [trans('tenant.errors.invalid_school_logo')],
            ]);
        }

        $ext = strtolower($m[1]) === 'jpeg' || strtolower($m[1]) === 'jpg' ? 'jpg' : strtolower($m[1]);
        $path = 'school-logos/'.Str::uuid()->toString().'.'.$ext;
        Storage::disk('public')->put($path, $binary);

        return Storage::url($path);
    }

    public function update(Request $request, Tenant $tenant): void
    {
        \DB::beginTransaction();

        $tenant->forceFill($this->formatParams($request, $tenant))->save();

        \DB::commit();
    }

    public function deletable(Tenant $tenant): void
    {
        $subscriptionExists = Subscription::whereTenantId($tenant->id)->exists();

        if ($subscriptionExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('tenant.tenant'), 'dependency' => trans('subscription.subscription')])]);
        }
    }
}

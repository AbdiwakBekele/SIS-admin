<?php

namespace App\Http\Resources;

use App\Enums\PlanFrequency;
use App\Enums\TenantStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $expiryDate = $this->expiry_date->value;

        if (! $expiryDate && $this->getMeta('trial_ends_at')) {
            $expiryDate = $this->getMeta('trial_ends_at');
        }

        $domainName = Arr::get(parse_url(config('app.url')), 'host');

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'address_display' => Arr::implode(Arr::notEmpty($this->address ?? [])),
            'contact_number' => $this->contact_number,
            'domain' => $this->domain,
            'custom_domain' => $this->custom_domain,
            'domain_url' => $this->custom_domain ?? 'https://'.$this->domain.'.'.$domainName,
            $this->mergeWhen(auth()->user()?->can('tenant:login'), [
                'login_url' => url('/login-as-tenant/'.$this->uuid),
            ]),
            'is_trial' => $this->is_trial ? true : false,
            'activation_token' => $this->getMeta('activation_token'),
            'status' => TenantStatus::getDetail($this->status),
            'expiry_date' => \Cal::date($expiryDate),
            'plan' => PlanResource::make($this->whenLoaded('plan')),
            'subscriptions' => SubscriptionResource::collection($this->whenLoaded('subscriptions')),
            'frequency' => PlanFrequency::getDetail($this->getMeta('frequency')),
            'currency' => \Currency::from($this->getMeta('currency')),
            'trial_period' => (int) $this->getMeta('trial_period'),
            'created_at' => \Cal::dateTime($this->created_at),
            'updated_at' => \Cal::dateTime($this->updated_at),
        ];
    }
}

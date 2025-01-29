<?php

namespace App\Http\Resources;

use App\Enums\PlanFrequency;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'code_number' => $this->code_number,
            'tenant' => TenantResource::make($this->whenLoaded('tenant')),
            'plan' => PlanResource::make($this->whenLoaded('plan')),
            'amount' => \Price::from($this->amount, $this->currency),
            'currency' => \Currency::from($this->currency),
            'frequency' => PlanFrequency::getDetail($this->frequency),
            'status' => $this->status ? true : false,
            'status_display' => $this->status ? trans('subscription.statuses.success') : trans('subscription.statuses.failed'),
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'reference_number' => $this->getMeta('reference_number') ?? '-',
            'is_online' => $this->getMeta('is_online') ? true : false,
            'name' => Arr::get($this->billing_address, 'name'),
            'email' => Arr::get($this->billing_address, 'email'),
            'address' => Arr::get($this->billing_address, 'address'),
            'created_at' => \Cal::dateTime($this->created_at),
            'updated_at' => \Cal::dateTime($this->updated_at),
        ];
    }
}

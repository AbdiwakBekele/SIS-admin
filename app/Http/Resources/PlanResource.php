<?php

namespace App\Http\Resources;

use App\Enums\PlanFrequency;
use App\Helpers\SysHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $modules = $this->getFeature('modules');

        $moduleDetails = collect($modules)->map(function ($module) {
            return [
                'label' => trans('plan.modules.'.$module),
                'value' => $module,
            ];
        });

        $moduleSummary = collect($modules)->map(function ($module) {
            return trans('plan.modules.'.$module);
        });

        $moduleSummary = match (true) {
            $moduleSummary->count() > 3 => $moduleSummary->take(3)
                ->push(trans('global.and_others', ['attribute' => ($moduleSummary->count() - 3)])),
            $moduleSummary->count() > 0 => $moduleSummary,
            default => []
        };

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'modules' => $modules,
            'module_details' => $moduleDetails,
            'module_summary' => $moduleSummary,
            'description' => $this->description,
            'inclusion' => $this->inclusion,
            'exclusion' => $this->exclusion,
            'show_inclusion_exclusion' => $this->show_inclusion_exclusion,
            'description_items' => $this->description_items,
            'inclusion_items' => $this->inclusion_items,
            'exclusion_items' => $this->exclusion_items,
            'is_active' => (bool) $this->getFeature('is_active'),
            'is_free' => (bool) $this->getFeature('is_free'),
            'is_default' => (bool) $this->getFeature('is_default'),
            'is_featured' => (bool) $this->getFeature('is_featured'),
            'is_visible' => (bool) $this->getFeature('is_visible'),
            'has_activation_charge' => (bool) $this->getFeature('has_activation_charge'),
            'allow_using_global_mail_service' => (bool) $this->getFeature('allow_using_global_mail_service'),
            'max_team_limit' => (int) $this->getFeature('max_team_limit'),
            'max_user_limit' => (int) $this->getFeature('max_user_limit'),
            'team_wise_limit' => (bool) $this->getFeature('team_wise_limit'),
            $this->mergeWhen($this->getFeature('team_wise_limit'), [
                'max_student_per_team_limit' => (int) $this->getFeature('max_student_per_team_limit'),
                'max_employee_per_team_limit' => (int) $this->getFeature('max_employee_per_team_limit'),
            ]),
            $this->mergeWhen(! $this->getFeature('team_wise_limit'), [
                'max_student_limit' => (int) $this->getFeature('max_student_limit'),
                'max_employee_limit' => (int) $this->getFeature('max_employee_limit'),
            ]),
            'enable_tax' => (bool) Arr::get($this->tax, 'is_enabled'),
            $this->mergeWhen(Arr::get($this->tax, 'is_enabled'), [
                'tax_label' => Arr::get($this->tax, 'label'),
                'tax_rate' => \Percent::from(Arr::get($this->tax, 'rate')),
                'tax_type_exclusive' => (bool) Arr::get($this->tax, 'is_exclusive'),
            ]),
            'enable_monthly_subscription' => (bool) $this->getMeta('enable_monthly_subscription', true),
            'enable_annual_subscription' => (bool) $this->getMeta('enable_annual_subscription', true),
            'price' => $this->getPrice(Arr::get($this->pricing, 'price', [])),
            'activation_charge' => $this->getActivationCharge(Arr::get($this->pricing, 'activation_charge', [])),
            'created_at' => \Cal::dateTime($this->created_at),
            'updated_at' => \Cal::dateTime($this->updated_at),
        ];
    }

    private function getPrice($price)
    {
        $prices = [];
        foreach (SysHelper::getAvailableCurrencies() as $currencyName) {
            foreach (PlanFrequency::getKeys() as $frequency) {
                $frequencyPrice = Arr::get($price, $frequency);
                $amount = Arr::get($frequencyPrice, $currencyName, 0);
                $prices[] = [
                    'frequency' => PlanFrequency::getDetail($frequency),
                    'currency' => \Currency::from($currencyName),
                    'amount' => \Price::from($amount, $currencyName),
                ];
            }
        }

        return $prices;
    }

    private function getActivationCharge($price)
    {
        $prices = [];
        foreach (SysHelper::getAvailableCurrencies() as $currencyName) {
            $amount = Arr::get($price, $currencyName, 0);
            $prices[] = [
                'currency' => \Currency::from($currencyName),
                'amount' => \Price::from($amount, $currencyName),
            ];
        }

        return $prices;
    }
}

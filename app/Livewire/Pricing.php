<?php

namespace App\Livewire;

use App\Helpers\SysHelper;
use App\Models\Plan;
use Livewire\Component;

class Pricing extends Component
{
    public $plans;

    public $currencies;

    public $frequency;

    public $defaultFrequency;

    public $defaultCurrency;

    public $defaultCurrencyDetail;

    public $columns;

    public function mount()
    {
        $this->plans = Plan::where('features->is_active', true)->where('features->is_visible', true)->get();
        $this->currencies = explode(',', config('config.system.currencies'));
        $this->frequency = 0;
        $this->defaultFrequency = $this->frequency ? 'annually' : 'monthly';
        $this->defaultCurrency = config('config.system.currency');
        $this->defaultCurrencyDetail = config('config.system.currency_detail');

        if ($this->plans->count() == 1) {
            $this->columns = 'col-lg-12 col-md-12';
        } elseif ($this->plans->count() == 2) {
            $this->columns = 'col-lg-6 col-md-12';
        } elseif ($this->plans->count() == 3 || $this->plans->count() % 3 == 0) {
            $this->columns = 'col-lg-4 col-md-12';
        } elseif ($this->plans->count() == 4 || $this->plans->count() % 4 == 0) {
            $this->columns = 'col-lg-3 col-md-12';
        } else {
            $this->columns = 'col-lg-4 col-md-6';
        }
    }

    public function render()
    {
        return view('livewire.pricing');
    }

    public function updatedFrequency()
    {
        $this->defaultFrequency = $this->frequency ? 'annually' : 'monthly';
        $this->dispatch('frequencyChanged', frequency: $this->defaultFrequency);
    }

    public function updatedDefaultCurrency()
    {
        $this->defaultCurrencyDetail = SysHelper::getCurrencyDetail($this->defaultCurrency);
        $this->dispatch('currencyChanged', currency: $this->defaultCurrencyDetail);
    }
}

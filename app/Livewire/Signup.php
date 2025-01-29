<?php

namespace App\Livewire;

use App\Actions\SendMailTemplate;
use App\Enums\TenantStatus;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\ActivateAccount;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;

class Signup extends Component {
    public $plans;

    public $tld;

    public $frequency;

    public $currency;

    public $email = '';

    public $name = '';

    public $contactNumber = '';

    public $domain = '';

    public $planId = '';

    public $terms = '';

    public $response = '';

    public $error = false;

    public $showResendLink = false;

    protected $listeners = ['frequencyChanged', 'currencyChanged'];

    public function mount() {
        $this->plans = Plan::where('features->is_active', true)->where('features->is_visible', true)->get();
        $this->tld = Arr::get(parse_url(config('app.url', $_SERVER['HTTP_HOST'])), 'host');
        $this->frequency = 'monthly';
        $this->currency = config('config.system.currency');
    }

    public function render() {
        return view('livewire.signup');
    }

    public function signup() {
        $this->showResendLink = false;

        $this->validate([
            'email' => 'required|email|unique:tenants,email|max:100',
            'name' => 'required|max:100',
            'contactNumber' => 'required|min:5|max:20',
            'domain' => 'required|min:3|max:30|alpha_num|not_in:' . config('config.tenant.unavailable_subdomain'),
            'planId' => 'required|integer',
            'terms' => 'accepted',
        ]);

        $plan = Plan::whereId($this->planId)->where('features->is_active', true)->where('features->is_visible', true)->first();

        if (! $plan) {
            $this->error = true;
            $this->response = 'Invalid plan selected.';

            return;
        }

        $existingDomain = Tenant::whereDomain($this->domain)->exists();

        if ($existingDomain) {
            $this->error = true;
            $this->response = 'This domain is already taken.';

            return;
        }

        $isTrial = config('config.tenant.trial_period', 0) ? 1 : 0;
        $trialPeriod = $isTrial ? config('config.tenant.trial_period', 0) : 0;
        $trialPeriod = (int) $trialPeriod;
        $trialEndsAt = $isTrial ? today()->addDays($trialPeriod)->format('Y-m-d') : null;

        if ($plan->getFeature('is_free')) {
            $isTrial = 0;
        }

        $tenant = Tenant::forceCreate([
            'email' => $this->email,
            'name' => $this->name,
            'domain' => $this->domain,
            'plan_id' => $this->planId,
            'is_trial' => $isTrial,
            'status' => TenantStatus::PENDING->value,
            'contact_number' => $this->contactNumber,
        ]);

        $meta = $tenant->meta;
        $meta['activation_token'] = Str::uuid();
        $meta['currency'] = $this->currency;
        $meta['frequency'] = $this->frequency;
        $meta['trial_period'] = $trialPeriod;
        $meta['trial_ends_at'] = $trialEndsAt;
        $tenant->meta = $meta;
        $tenant->save();

        $domainName = Arr::get(parse_url(config('app.url')), 'host');
        $url = 'http://' . $tenant->domain . '.' . $domainName;

        (new SendMailTemplate)->execute(
            email: $tenant->email,
            code: 'customer-signup',
            variables: [
                'name' => $tenant->name,
                'url' => $url,
                'activation_token' => $tenant->getMeta('activation_token'),
            ]
        );

        (new User)->forceFill([
            'email' => $tenant->email,
        ])->notify(new ActivateAccount($tenant));

        $this->email = '';
        $this->name = '';
        $this->contactNumber = '';
        $this->domain = '';
        $this->planId = '';

        $this->response = 'Please check your email to complete the registration.';
        $this->error = false;
    }

    #[On('frequencyChanged')]
    public function frequencyChanged($frequency) {
        $this->frequency = $frequency;
    }

    #[On('currencyChanged')]
    public function currencyChanged($currency) {
        $this->currency = Arr::get($currency, 'name');
    }

    public function showResendOption() {
        $this->showResendLink = ! $this->showResendLink;
    }
}

<?php

namespace App\Livewire;

use App\Enums\TenantStatus;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\ActivateAccount;
use Livewire\Component;

class ResendActivation extends Component
{
    public $email = '';

    public $response = '';

    public $error = false;

    public function mount() {}

    public function render()
    {
        return view('livewire.resend-activation');
    }

    public function resend()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $tenant = Tenant::whereEmail($this->email)->whereStatus(TenantStatus::PENDING->value)->first();

        if (! $tenant) {
            $this->response = 'We could not find this email.';
            $this->error = true;

            return;
        }

        (new User)->forceFill([
            'email' => $tenant->email,
        ])->notify(new ActivateAccount($tenant));

        $this->email = '';

        $this->error = false;
        $this->response = 'Please check your email for further activation.';
    }
}

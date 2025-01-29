<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class CookieConsent extends Component
{
    public $accepted;

    public function mount()
    {
        $this->accepted = Cookie::has('cookie-consent');
    }

    public function render()
    {
        return view('livewire.cookie-consent');
    }

    public function accept()
    {
        Cookie::queue(Cookie::forever('cookie-consent', true));
        $this->accepted = true;
    }
}

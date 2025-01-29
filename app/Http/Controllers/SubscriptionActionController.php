<?php

namespace App\Http\Controllers;

use App\Models\Subscription;

class SubscriptionActionController extends Controller
{
    public function print(Subscription $subscription)
    {
        if (! $subscription->status) {
            abort(404);
        }

        $subscription->load('plan');

        return view('print.subscription', compact('subscription'));
    }
}

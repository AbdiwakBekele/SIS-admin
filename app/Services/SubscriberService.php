<?php

namespace App\Services;

use App\Helpers\CalHelper;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberService
{
    public function create(Request $request): Subscriber
    {
        \DB::beginTransaction();

        $subscriber = Subscriber::forceCreate($this->formatParams($request));

        \DB::commit();

        return $subscriber;
    }

    private function formatParams(Request $request, ?Subscriber $subscriber = null): array
    {
        $formatted = [
            'name' => $request->name,
            'email' => $request->email,
            'unsubscribed_at' => CalHelper::storeDateTime($request->unsubscribed_at),
        ];

        if (! $subscriber) {
        }

        return $formatted;
    }

    public function update(Request $request, Subscriber $subscriber): void
    {
        \DB::beginTransaction();

        $subscriber->forceFill($this->formatParams($request, $subscriber))->save();

        \DB::commit();
    }

    public function deletable(Subscriber $subscriber): void {}
}

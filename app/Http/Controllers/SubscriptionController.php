<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\SubscriptionListService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function preRequisite(Request $request, SubscriptionService $service)
    {
        $this->authorize('preRequisite', Subscription::class);

        return response()->ok($service->preRequisite($request));
    }

    public function index(Request $request, SubscriptionListService $service)
    {
        $this->authorize('viewAny', Subscription::class);

        return $service->paginate($request);
    }

    public function store(SubscriptionRequest $request, SubscriptionService $service)
    {
        $this->authorize('create', Subscription::class);

        $subscription = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('subscription.subscription')]),
            'subscription' => SubscriptionResource::make($subscription),
        ]);
    }

    public function show(Subscription $subscription, SubscriptionService $service)
    {
        $this->authorize('view', $subscription);

        $subscription->load('tenant', 'plan');

        return SubscriptionResource::make($subscription);
    }

    public function update(SubscriptionRequest $request, Subscription $subscription, SubscriptionService $service)
    {
        $this->authorize('update', $subscription);

        $service->update($request, $subscription);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('subscription.subscription')]),
        ]);
    }

    public function destroy(Subscription $subscription, SubscriptionService $service)
    {
        $this->authorize('delete', $subscription);

        $service->deletable($subscription);

        $subscription->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('subscription.subscription')]),
        ]);
    }
}

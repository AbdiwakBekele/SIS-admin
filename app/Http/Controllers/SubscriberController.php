<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use App\Services\SubscriberListService;
use App\Services\SubscriberService;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function index(Request $request, SubscriberListService $service)
    {
        $this->authorize('viewAny', Subscriber::class);

        return $service->paginate($request);
    }

    public function store(SubscriberRequest $request, SubscriberService $service)
    {
        $this->authorize('create', Subscriber::class);

        $subscriber = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('subscriber.subscriber')]),
            'subscriber' => SubscriberResource::make($subscriber),
        ]);
    }

    public function show(Subscriber $subscriber, SubscriberService $service)
    {
        $this->authorize('view', $subscriber);

        return SubscriberResource::make($subscriber);
    }

    public function update(SubscriberRequest $request, Subscriber $subscriber, SubscriberService $service)
    {
        $this->authorize('update', $subscriber);

        $service->update($request, $subscriber);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('subscriber.subscriber')]),
        ]);
    }

    public function destroy(Subscriber $subscriber, SubscriberService $service)
    {
        $this->authorize('delete', $subscriber);

        $service->deletable($subscriber);

        $subscriber->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('subscriber.subscriber')]),
        ]);
    }
}

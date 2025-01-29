<?php

namespace App\Services;

use App\Contracts\ListGenerator;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriberListService extends ListGenerator
{
    protected $allowedSorts = ['created_at', 'name', 'email', 'unsubscribed_at'];

    protected $defaultSort = 'email';

    protected $defaultOrder = 'asc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('subscriber.props.name'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'email',
                'label' => trans('subscriber.props.email'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'unsubscribedAt',
                'label' => trans('subscriber.props.unsubscribed_at'),
                'print_label' => 'unsubscribed_at.formatted',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'createdAt',
                'label' => trans('general.created_at'),
                'print_label' => 'created_at.formatted',
                'sortable' => true,
                'visibility' => true,
            ],
        ];

        if (request()->ajax()) {
            $headers[] = $this->actionHeader;
        }

        return $headers;
    }

    public function filter(Request $request): Builder
    {
        return Subscriber::query()
            ->filter([
                'App\QueryFilters\LikeMatch:name',
                'App\QueryFilters\LikeMatch:email',
                'App\QueryFilters\UuidMatch',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return SubscriberResource::collection($this->filter($request)
            ->orderBy($this->getSort(), $this->getOrder())
            ->paginate((int) $this->getPageLength(), ['*'], 'current_page'))
            ->additional([
                'headers' => $this->getHeaders(),
                'meta' => [
                    'allowed_sorts' => $this->allowedSorts,
                    'default_sort' => $this->defaultSort,
                    'default_order' => $this->defaultOrder,
                ],
            ]);
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        return $this->paginate($request);
    }
}

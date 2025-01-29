<?php

namespace App\Services;

use App\Contracts\ListGenerator;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionListService extends ListGenerator
{
    protected $allowedSorts = ['created_at'];

    protected $defaultSort = 'created_at';

    protected $defaultOrder = 'desc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'codeNumber',
                'label' => trans('subscription.props.code_number'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'tenant',
                'label' => trans('tenant.tenant'),
                'print_label' => 'tenant.domain',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'plan',
                'label' => trans('plan.plan'),
                'print_label' => 'plan.name',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'amount',
                'label' => trans('subscription.props.amount'),
                'print_label' => 'amount.formatted',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'startDate',
                'label' => trans('subscription.props.start_date'),
                'print_label' => 'start_date.formatted',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'expiryDate',
                'label' => trans('subscription.props.expiry_date'),
                'print_label' => 'expiry_date.formatted',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'date',
                'label' => trans('subscription.props.date'),
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
        $domain = $request->query('domain');
        $showFailed = $request->boolean('show_failed');

        return Subscription::query()
            ->with('plan', 'tenant')
            ->when($domain, function ($q, $domain) {
                $q->whereHas('tenant', function ($q) use ($domain) {
                    $q->whereDomain($domain);
                });
            })
            ->when($showFailed, function ($q) {
                $q->whereNotNull('status');
            }, function ($q) {
                $q->whereStatus(1);
            })
            ->filter([
                'App\QueryFilters\UuidMatch',
                'App\QueryFilters\ExactMatch:code_number',
                'App\QueryFilters\DateBetween:start_date,expiry_date,created_at,datetime',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return SubscriptionResource::collection($this->filter($request)
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

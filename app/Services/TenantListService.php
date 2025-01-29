<?php

namespace App\Services;

use App\Contracts\ListGenerator;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TenantListService extends ListGenerator
{
    protected $allowedSorts = ['created_at', 'domain', 'name', 'email', 'expiry_date'];

    protected $defaultSort = 'created_at';

    protected $defaultOrder = 'desc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('tenant.props.name'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'email',
                'label' => trans('tenant.props.email'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'contactNumber',
                'label' => trans('tenant.props.contact_number'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'domain',
                'label' => trans('tenant.props.domain'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'status',
                'label' => trans('tenant.props.status'),
                'print_label' => 'status.label',
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
                'key' => 'expiryDate',
                'label' => trans('tenant.props.expiry_date'),
                'print_label' => 'expiry_date.formatted',
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
        return Tenant::query()
            ->with('plan')
            ->filter([
                'App\QueryFilters\LikeMatch:domain',
                'App\QueryFilters\LikeMatch:name',
                'App\QueryFilters\LikeMatch:email',
                'App\QueryFilters\UuidMatch',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return TenantResource::collection($this->filter($request)
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

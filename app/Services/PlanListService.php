<?php

namespace App\Services;

use App\Contracts\ListGenerator;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlanListService extends ListGenerator
{
    protected $allowedSorts = ['created_at', 'name', 'code', 'position'];

    protected $defaultSort = 'position';

    protected $defaultOrder = 'asc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('plan.props.name'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'code',
                'label' => trans('plan.props.code'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'maxTeamLimit',
                'label' => trans('plan.props.max_team_limit'),
                'print_label' => 'max_team_limit',
                'print_sub_label' => 'max_user_limit',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'maxStudentLimit',
                'label' => trans('plan.props.max_student_limit'),
                'print_label' => 'max_student_limit',
                'print_sub_label' => 'max_employee_limit',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'maxStudentPerTeamLimit',
                'label' => trans('plan.props.max_student_per_team_limit'),
                'print_label' => 'max_student_per_team_limit',
                'print_sub_label' => 'max_employee_per_team_limit',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'isActive',
                'label' => trans('plan.props.active'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'isFree',
                'label' => trans('plan.props.free'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'hasActivationCharge',
                'label' => trans('plan.props.activation_charge'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'enableTax',
                'label' => trans('plan.props.tax'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'isVisible',
                'label' => trans('plan.props.visible'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'modules',
                'label' => trans('plan.props.modules'),
                'type' => 'array',
                'print_label' => 'module_details',
                'print_key' => 'label',
                'sortable' => false,
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
        return Plan::query()
            ->filter([
                'App\QueryFilters\LikeMatch:name',
                'App\QueryFilters\UuidMatch',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        if ($request->query('all')) {
            return PlanResource::collection($this->filter($request)
                ->orderBy('position', 'asc')
                ->get());
        }

        return PlanResource::collection($this->filter($request)
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

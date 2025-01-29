<?php

namespace App\Http\Controllers;

use App\Http\Resources\QueryResource;
use App\Models\Query;
use App\Services\QueryListService;
use App\Services\QueryService;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function index(Request $request, QueryListService $service)
    {
        $this->authorize('viewAny', Query::class);

        return $service->paginate($request);
    }

    public function show(Query $query, QueryService $service)
    {
        $this->authorize('view', $query);

        return QueryResource::make($query);
    }

    public function destroy(Query $query, QueryService $service)
    {
        $this->authorize('delete', $query);

        $service->deletable($query);

        $query->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('query.query')]),
        ]);
    }
}

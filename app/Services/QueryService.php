<?php

namespace App\Services;

use App\Models\Query;
use Illuminate\Http\Request;

class QueryService
{
    public function create(Request $request): Query
    {
        \DB::beginTransaction();

        $query = Query::forceCreate($this->formatParams($request));

        \DB::commit();

        return $query;
    }

    private function formatParams(Request $request, ?Query $query = null): array
    {
        $formatted = [
            'name' => $request->name,
        ];

        if (! $query) {
        }

        return $formatted;
    }

    public function update(Request $request, Query $query): void
    {
        \DB::beginTransaction();

        $query->forceFill($this->formatParams($request, $query))->save();

        \DB::commit();
    }

    public function deletable(Query $query): void {}
}

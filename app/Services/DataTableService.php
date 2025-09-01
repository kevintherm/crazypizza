<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DataTableService
{
    public static function make($modelClass, Request $request, array $searchable = ['name'], array $with = [])
    {
        $search   = $request->input('search');
        $filters  = $request->input('filters', []);
        $sort     = $request->input('sort', 'created_at');
        $sortDesc = $request->boolean('sort_desc', true);
        $perPage  = max(1, $request->integer('per_page', 8));

        $query = $modelClass::query()->whereNull('deleted_at');

        if ($with) {
            $query->with($with);
        }

        if ($search) {
            $query->where(function($q) use ($searchable, $search) {
                foreach ($searchable as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        foreach ($filters as $filter) {
            if (!Schema::hasColumn($query->getModel()->getTable(), $filter['column'])) {
                continue;
            }

            switch ($filter['type']) {
                case 'range':
                    $query->whereBetween($filter['column'], [
                        $filter['min'] ?? 0,
                        $filter['max'] ?? null
                    ]);
                    break;
                default:
                    $query->where($filter['column'], $filter['value']);
                    break;
            }
        }

        return $query->orderBy($sort, $sortDesc ? 'desc' : 'asc')
                     ->paginate($perPage);
    }
}

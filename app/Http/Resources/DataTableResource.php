<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class DataTableResource extends JsonResource
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function toArray(Request $request)
    {
        $lastPage = $this->collection->lastPage();
        $current = $this->collection->currentPage();

        $pages = collect(range(max(1, $current - 2), min($lastPage, $current + 2)))
            ->push($lastPage)
            ->unique()
            ->sort()
            ->values();

        return [
            'current_page' => $current,
            'per_page' => $this->collection->perPage(),
            'total' => $this->collection->total(),
            'pages' => $pages,
            'has_next_page' => $this->collection->hasMorePages(),
            'has_previous_page' => $current > 1,
            'data' => $this->collection->items(),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Helper;
use App\Models\Pizza;
use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Services\DataTableService;
use App\Http\Requests\PizzaRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataTableResource;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function dataTable(Request $request)
    {
        return $this->safe(function () use ($request) {
            $reviews = DataTableService::make(Review::class, $request, ['comment'], ['order', 'pizza']);
            return ApiResponse::success('Pizzas fetched successfully.', new DataTableResource($reviews));
        });
    }

    public function delete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $review = CrudService::delete(Review::class, $request->id, $request->column);
            return ApiResponse::success("Deleted successfully.", $review);
        });
    }

    public function bulkDelete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:reviews,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), errors: $validator->errors(), status: 400);
            }

            CrudService::bulkDelete(Review::class, $request->ids);

            return ApiResponse::success("Pizzas deleted successfully.", null, 200);
        });
    }
}

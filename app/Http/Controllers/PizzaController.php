<?php

namespace App\Http\Controllers;

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

class PizzaController extends Controller
{
    public function dataTable(Request $request)
    {
        return $this->safe(function () use ($request) {
            $pizzas = DataTableService::make(Pizza::class, $request, ['name']);
            return ApiResponse::success('Pizzas fetched successfully.', new DataTableResource($pizzas));
        });
    }

    public function createUpdate(PizzaRequest $request)
    {
        return $this->safe(function () use ($request) {

            $pizza = Pizza::updateOrCreate(
                ['id' => $request->input('id')],
                $request->validated()
            );

            if ($request->hasFile('image')) {
                $pizza->image = Helper::uploadFile($request->file('image'), 'pizzas');
                $pizza->save();
            }

            return ApiResponse::success("{$pizza->name} saved successfully.", $pizza);
        });
    }

    public function delete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $pizza = CrudService::delete(Pizza::class, $request->id, $request->column);
            return ApiResponse::success("Deleted successfully.", $pizza);
        });
    }

    public function bulkDelete(Request $request)
    {
        return $this->safe(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:pizzas,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), errors: $validator->errors(), status: 400);
            }

            CrudService::bulkDelete(Pizza::class, $request->ids);

            return ApiResponse::success("Pizzas deleted successfully.", null, 200);
        });
    }
}

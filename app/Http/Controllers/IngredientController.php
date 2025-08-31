<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientRequest;
use App\Services\CrudService;
use DB;
use Helper;
use Validator;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Services\DataTableService;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\DataTableResource;

class IngredientController extends Controller
{

    public function dataTable(Request $request)
    {
        try {
            $ingredients = DataTableService::make(Ingredient::class, $request, ['name']);
            return ApiResponse::success('Ingredients fetched successfully.', new DataTableResource($ingredients));
        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }

    public function createUpdate(IngredientRequest $request)
    {
        try {
            DB::beginTransaction();

            $ingredient = Ingredient::updateOrCreate(
                ['id' => $request->input('id')],
                $request->validated()
            );

            if ($request->hasFile('image')) {
                $ingredient->image = Helper::uploadFile($request->file('image'), 'ingredients');
                $ingredient->save();
            }

            DB::commit();
            return ApiResponse::success("{$ingredient->name} saved successfully.", $ingredient);

        } catch (\Throwable $e) {
            DB::rollBack();
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $ingredient = CrudService::delete(Ingredient::class, $request->id, $request->column);
            return ApiResponse::success("Deleted successfully.", $ingredient);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:ingredients,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), errors: $validator->errors(), status: 400);
            }

            CrudService::bulkDelete(Ingredient::class, $request->ids);

            DB::commit();

            return ApiResponse::success("Ingredients deleted successfully.", null, 200);

        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }
}

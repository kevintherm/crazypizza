<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use DB;
use Helper;
use Validator;

class IngredientController extends Controller
{
    public function dataTable(Request $request)
    {
        try {

            $ingredients = DB::table('ingredients')
                ->select('*')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return ApiResponse::success('Ingredients fetched successfully.', $ingredients);

        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }

    public function createUpdate(Request $request)
    {
        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id' => 'nullable|integer|exists:ingredients,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048',
                'calories_per_unit' => 'nullable|integer|min:0',
                'unit' => 'nullable|in:' . implode(',', array_keys(Ingredient::UNITS)),
                'is_vegan' => 'nullable|boolean',
                'is_gluten_free' => 'nullable|boolean',
                'stock_quantity' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), status: 400);
            }

            $ingredient = Ingredient::updateOrCreate(
                ['id' => $request->input('id')],
                $validator->validated()
            );

            DB::commit();

            return ApiResponse::success("{$ingredient->name} saved successfully.", $ingredient, 200);

        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:ingredients,id',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), status: 400);
            }

            $ingredient = Ingredient::find($request->input('id'));
            $ingredient->delete();

            DB::commit();

            return ApiResponse::success("{$ingredient->name} deleted successfully.", null, 200);

        }

        catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }
}

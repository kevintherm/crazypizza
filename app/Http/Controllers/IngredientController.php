<?php

namespace App\Http\Controllers;

use DB;
use Helper;
use Storage;
use Validator;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Schema;

class IngredientController extends Controller
{
    public function dataTable(Request $request)
    {
        try {

            $search = $request->input('search');
            $sort = $request->input('sort', 'created_at');
            $sortDesc = $request->boolean('sort_desc', true);
            $perPage = $request->integer('per_page', 8);

            $ingredients = DB::table('ingredients')
                ->select('*')
                ->whereNull('deleted_at')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy($sort, $sortDesc ? 'desc' : 'asc')
                ->simplePaginate($perPage);

            $total = DB::table('ingredients')->whereNull('deleted_at')->count();

            $lastPage = $perPage == 0 ? 1 : (int) ceil($total / $perPage);
            $pages = collect(range(
                max(1, $ingredients->currentPage() - 2),
                min($lastPage, $ingredients->currentPage() + 2)
            ))->values();

            $pages->push($lastPage);
            $pages = $pages->unique()->sort()->values();

            return ApiResponse::success('Ingredients fetched successfully.', [
                'current_page' => $ingredients->currentPage(),
                'per_page' => $ingredients->perPage(),
                'total' => $total,
                'pages' => $pages,
                'has_next_page' => $ingredients->hasMorePages(),
                'has_previous_page' => $ingredients->currentPage() > 1,
                'data' => $ingredients->items(),
            ]);

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

            if ($request->hasFile('image')) {
                $ingredient->image = Helper::uploadFile($request->file('image'), 'ingredients');
                $ingredient->save();
            }

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
                'column' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error($validator->errors()->first(), status: 400);
            }

            $ingredient = Ingredient::find($request->input('id'));

            $deletingColumn = $request->input('column', null);

            if ($request->has('column') && $request->filled('column')) {

                if ($deletingColumn == 'image') {
                    Helper::deleteFile($ingredient->image);
                    $ingredient->update(['image' => null]);
                }

            } else {
                $ingredient->delete();
            }

            DB::commit();

            return ApiResponse::success(
                message: ucfirst($deletingColumn ?? $ingredient->name) . " deleted successfully.",
                data: $ingredient,
                status: 200
            );

        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
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
                return ApiResponse::error($validator->errors()->first(), status: 400);
            }

            Ingredient::destroy($request->input('ids'));

            DB::commit();

            return ApiResponse::success("Ingredients deleted successfully.", null, 200);

        } catch (\Throwable $e) {
            Helper::LogThrowable($request, __FILE__, $e);
            return ApiResponse::error($e->getMessage(), status: 500);
        }
    }
}

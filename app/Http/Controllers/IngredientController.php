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
            $filters = $request->input('filters', []);
            $sort = $request->input('sort', 'created_at');
            $sortDesc = $request->boolean('sort_desc', true);
            $perPage = max(1, $request->integer('per_page', 8));

            $ingredients = Ingredient::query()
                ->whereNull('deleted_at')
                ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->when($filters, function ($q) use ($filters) {
                    foreach ($filters as $filter) {
                        // Validate column
                        if (!Schema::hasColumn('ingredients', $filter['column'])) return;

                        switch ($filter['type']) {
                            case 'range':
                                $q->whereBetween($filter['column'], [$filter['min'] || 0, $filter['max']]);
                                break;

                            default:
                                $q->where($filter['column'], $filter['value']);
                                break;
                        }
                    }
                })
                ->orderBy($sort, $sortDesc ? 'desc' : 'asc')
                ->paginate($perPage);

            $lastPage = $ingredients->lastPage();
            $current = $ingredients->currentPage();

            $pages = collect(range(max(1, $current - 2), min($lastPage, $current + 2)))
                ->push($lastPage)
                ->unique()
                ->sort()
                ->values();

            return ApiResponse::success('Ingredients fetched successfully.', [
                'current_page' => $current,
                'per_page' => $ingredients->perPage(),
                'total' => $ingredients->total(),
                'pages' => $pages,
                'has_next_page' => $ingredients->hasMorePages(),
                'has_previous_page' => $current > 1,
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

<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use DB;
use Illuminate\Http\Request;
use Validator;

class IngredientController extends Controller
{
    public function dataTable()
    {
        try {

            $ingredients = DB::table('ingredients')
                ->select('*')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'Ingredients fetched successfully.',
                'data' => $ingredients,
            ]);

        }

        catch (\Exception $e) {}
        catch (\Throwable $e) {}

        return response()->json([
            'message' => 'An error occurred while fetching the ingredients.',
        ], 500);
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048',
                'calories_per_unit' => 'nullable|integer|min:0',
                'unit' => 'nullable|in:' . implode(',', array_keys(Ingredient::UNITS)),
                'is_vegan' => 'nullable|boolean',
                'is_gluten_free' => 'nullable|boolean',
                'stock_quantity' => 'nullable|integer|min:0',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 400);
            }

            Ingredient::create($validator->validated());

            DB::commit();

            return response()->json([
                'message' => 'Ingredient added successfully.',
            ], 201);

        }

        catch (\Exception $e) {}
        catch (\Throwable $e) {}

        return response()->json([
            'message' => 'An error occurred while adding the ingredient.',
        ], 500);
    }
}

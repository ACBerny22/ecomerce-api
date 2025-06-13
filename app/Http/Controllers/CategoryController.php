<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function fetchCategories(Request $request)
    {
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }


    public function createCategory(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'string|required|unique:category,name',
            'description' => 'string'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validation->errors()
            ], 400);
        }

        $category = Category::create($request->all());
        if (!$category) {
            return response()->json([
                'status' => false,
                'error' => "Error al registrar la categoria"
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }
}

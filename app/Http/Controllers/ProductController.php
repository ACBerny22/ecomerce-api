<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function fetchProducts(Request $request)
    {

        $queryParams = $request->all();

        $query = Product::with(["category" => function ($subQuery) {
            $subQuery->select("id", "name", "description");
        }]);

        $products = $query->get();

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    public function createProduct(Request $request)
    {
        $incommingFields = $request->all();
        $validation = Validator::make($incommingFields, [
            'name' => 'required|string|unique:product,name',
            'description' => 'string',
            'price' => 'required|numeric',
            'stock' => 'integer',
            'category_id' => 'required|integer',
            'image' => 'string',

        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validation->errors()
            ], 400);
        }

        $product = Product::create($incommingFields);

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }
}

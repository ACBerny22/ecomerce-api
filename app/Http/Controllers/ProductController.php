<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function fetchProducts(Request $request)
    {

        $queryParams = $request->all();

        // That's how you get the fillables or any other attribute in a model.
        $filtrables = (new Product())->getFillable();

        $query = Product::with(["category" => function ($subQuery) {
            $subQuery->select("id", "name", "description");
        }]);

        foreach ($queryParams as $key => $value) {
            if (in_array($key, $filtrables)) {
                $query->where($key, 'like', "%$value%");
            }
        }

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

    public function editProduct(Request $request)
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
    }

    public function deleteProduct(Request $request, $id)
    {
        $product = Product::find(intval($id));
        if (!$product) {
            return response()->json([
                "status" => false,
                "error" => "El producto con id: " . $id . " no existe"
            ], 400);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        Product::where('id', $id)->delete();

        return response()->json([
            "status" => true,
            "data" => $product
        ]);
    }

    public function updateProductImage(Request $request)
    {
        $product = Product::find($request->id);
        if (!$product) return response()->json([
            "status" => false,
            "error" => "El producto con el id " . $request->id . " no existe."
        ]);

        $validation = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
        ]);

        if ($validation->fails()) {
            return response()->json([
                "status" => false,
                "error" => $validation->errors()
            ], 400);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $imagePath = $request->file('image')->store('products', 'public');
        $product->update([
            "image" => $imagePath
        ]);

        return response()->json([
            "status" => true,
            "data" => $product
        ]);
    }
}

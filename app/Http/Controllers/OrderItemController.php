<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public static function processItemsInCreateOrder($items)
    {
        dd($items);
    }

    public function createOrderItems(Request $request)
    {
        $incommingFields = $request->all();

        $validation = Validator::make($incommingFields, [
            "order_id" => "required|integer",
            "product_id" => "required|integer",
            "quantity" => "required|integer",
        ]);

        if ($validation->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validation->errors(),
            ], 400);
        }

        $product = Product::find($incommingFields["product_id"])->toArray();

        $payload = [
            ...$incommingFields,
            "price" => floatval($product["price"]),
            "subtotal" => $incommingFields["quantity"] * $product["price"]
        ];

        $orderItem = OrderItem::create($payload);
        return response()->json([
            "status" => true,
            "data" => $orderItem,
        ], 400);
    }
}

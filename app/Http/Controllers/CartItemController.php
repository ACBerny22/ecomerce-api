<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{
    public function fetchCart(Request $request)
    {
        $user = $request->user();
        $cartItems = CartItem::where("user_id", $user->id)
            ->with([
                "user" => function ($subQuery) {
                    $subQuery->select("id", "name");
                },
                "product" => function ($subQuery) {
                    $subQuery->select("id", "name", "price", "stock");
                }
            ])
            ->get();

        return response()->json([
            "status" => true,
            "data" => $cartItems
        ]);
    }

    public function createCartItem(Request $request)
    {
        $incommingFields = $request->all();
        $validation = Validator::make($incommingFields, [
            "product_id" => "required|integer",
            "quantity" => "required|integer"
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validation->errors()
            ], 400);
        }

        $payload = [
            ...$incommingFields,
            "user_id" => $request->user()->id
        ];

        $cartItem = CartItem::create($payload);

        return response()->json([
            'status' => true,
            'data' => $cartItem
        ]);
    }

    public function deleteCartItem(Request $request)
    {

        $cartItem = CartItem::find($request->id);
        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'error' => "El elemento no existe en el carrito"
            ], 400);
        }

        CartItem::where("id", $request->id)->delete();

        return response()->json([
            'status' => true,
            'data' => $cartItem
        ]);
    }
}

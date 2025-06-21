<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\OrderItemController;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function fetchOrders(Request $request)
    {
        $queryParams = $request->all();
        $filtrables = (new Order())->getFillable();

        $query = Order::with([
            "user" => function ($subQuery) {
                $subQuery->select("id", "name");
            },
            "items" => function ($subQuery) {
                $subQuery->with("product")->get();
            }
        ]);

        foreach ($queryParams as $key => $value) {
            if (in_array($key, $filtrables)) {
                $query->where($key, 'like', "%$value%");
            }
        }

        $orders = $query->get();

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    public function createOrder(Request $request)
    {
        $incommingFields = $request->all();
        $validation = Validator::make($incommingFields, [
            "shipping_address" => "required|string"
        ]);

        if ($validation->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validation->errors(),
            ], 400);
        }

        if (isset($incommingFields["items"])) {
            OrderItemController::processItemsInCreateOrder($incommingFields["items"]);
        } else {
            $payload = [
                ...$incommingFields,
                "status" => "pending",
                "user_id" => $request->user()->id,
                "total_amount" => 0
            ];
        }

        $order = Order::create($payload);

        return response()->json([
            "status" => true,
            "data" => $order
        ]);
    }

    // Internal use only.
    public function processItemsInCreateOrder($items)
    {
        dd($items);
    }
}

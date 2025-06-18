<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function fetchOrders(Request $request)
    {
        $queryParams = $request->all();
        $filtrables = (new Order())->getFillable();

        $query = Order::with(["user" => function ($subQuery) {
            $subQuery->select("id", "name");
        }]);

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
}

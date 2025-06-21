<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_item";

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }

    public $fillable = [
        "order_id",
        "product_id",
        "price",
        "quantity",
        "subtotal",
    ];
}

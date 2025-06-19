<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $table = "cart_items";

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }

    protected $fillable = [
        "user_id",
        "product_id",
        "quantity",
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $table = "cart_item";

    protected function user()
    {
        return $this->belongsTo("user", "user_id");
    }

    protected $fillable = [
        "user_id",
        "product_id",
        "quantity",
    ];
}

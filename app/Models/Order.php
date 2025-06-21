<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{

    protected $table = "order";

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    //
    protected $fillable = [
        "user_id",
        "total_amount",
        "status",
        "shipping_address"
    ];
}

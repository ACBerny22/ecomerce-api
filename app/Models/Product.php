<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    //
    protected $table = 'product';

    protected $searchables = [
        'name' => 'name',
        'category_id' => 'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    protected $fillable = [
        "name",
        "description",
        "price",
        "stock",
        "category_id",
        "image",
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $fillable = [
        'products_id',
        'url',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}

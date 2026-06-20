<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'slug',
        'description',
    ];

    /**
     * Gunakan slug sebagai route key — URL jadi /produk/nama-produk/edit
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    /**
     * Auto-generate slug dari name setiap create & update.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    /**
     * Relasi ke ProductGallery.
     */
    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'products_id');
    }
}

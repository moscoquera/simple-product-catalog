<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='products';
    protected $fillable=['name','description','category_id','weight','usd_price'];

    function images(){
        return $this->hasMany(ProductImage::class)->orderBy('id');
    }

    function category(){
        return $this->belongsTo(Category::class);
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
            $product->images()->delete();
        });
    }
}

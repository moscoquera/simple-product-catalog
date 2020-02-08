<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='categories';

    protected $fillable=['name','image','parent_id'];

    public function parent(){
       return $this->belongsTo(Category::class,'parent_id');
    }

    public function childs(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function scopeLeaf($query){
        return $query->doesntHave('childs');
    }
    public function scopeTop($query){
        return $query->where('parent_id',null);
    }

}

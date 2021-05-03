<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable=['border_product_id','product_id','image','title'];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function borderProduct(){
        return $this->hasOne(BorderProduct::class,'id','border_product_id');
    }

    
}

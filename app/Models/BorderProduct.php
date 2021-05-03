<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class BorderProduct extends Model
{
    protected $fillable=['title','price'];

    public static function getAllProduct(){
        return BorderProduct::orderBy('id','desc')->paginate(10);
    }
    
    
    public function images(){
        return $this->hasMany(ProductImage::class);
    }

}

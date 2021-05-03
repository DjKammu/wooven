<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class BorderProduct extends Model
{
    protected $fillable=['title'];

    public function cat_info(){
        return $this->hasOne('App\Models\Category','id','cat_id');
    }

    public function sub_cat_info(){
        return $this->hasOne('App\Models\Category','id','child_cat_id');
    }

    public static function getAllProduct(){
        return Product::with(['cat_info','sub_cat_info'])->orderBy('id','desc')->paginate(10);
    }
    
    public function rel_prods(){
        return $this->hasMany('App\Models\Product','cat_id','cat_id')->where('status','active')->orderBy('id','DESC')->limit(8);
    }
    public function getReview(){
        return $this->hasMany('App\Models\ProductReview','product_id','id')->with('user_info')->where('status','active')->orderBy('id','DESC');
    }
    

}

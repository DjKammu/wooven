<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;

use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return $category;
        return view('backend.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|required',
            // 'description'=>'string|nullable',
            // 'photo'=>'string|required',
            // 'size'=>'nullable',
            // 'stock'=>"required|numeric",
            // 'cat_id'=>'required|exists:categories,id',
            // 'brand_id'=>'nullable|exists:brands,id',
            // 'child_cat_id'=>'nullable|exists:categories,id',
            // 'is_featured'=>'sometimes|in:1',
            'status'=>'required|in:active,inactive',
            // 'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            // 'discount'=>'nullable|numeric'
        ]);
        
        $data=$request->all();

         if($request->hasfile('inner.image'))
         {
            $images = [];
            foreach($request->file('inner.image') as $k => $file)
            {
                $name = time().$k.'.'.$file->extension();
                 \Storage::putFileAs(
                    'products', $file, $name
                );
                $images[] =  '/storage/products/'.$name;  
            }

            $data['inner']['image'] = $images;
         }

         $imageInput = @collect( $data['inner']['image']);
         $nameInput = @collect( $data['inner']['name']);

         $imagesData = [];

         $r = [];
      
        $imagesData = @$imageInput->map(function($image,$i) use ($r,$nameInput){
             $r['image'] = $image;
             $r['title'] = $nameInput[$i];
             return $r;
        });
    
        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
       
        $product = Product::create($data);

        $productImages = ($productImages) ?? $product->images()->createMany($imagesData);

        if($product && $productImages){
            request()->session()->flash('success','Inner Product Successfully added');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product=Product::findOrFail($id);
        $images = @$product->images()->get();
        // return $items;
        return view('backend.product.edit',compact('product','images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|required',
            // 'description'=>'string|nullable',
            // 'photo'=>'string|required',
            // 'size'=>'nullable',
            // 'stock'=>"required|numeric",
            // 'cat_id'=>'required|exists:categories,id',
            // 'brand_id'=>'nullable|exists:brands,id',
            // 'child_cat_id'=>'nullable|exists:categories,id',
            // 'is_featured'=>'sometimes|in:1',
            'status'=>'required|in:active,inactive',
            // 'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            // 'discount'=>'nullable|numeric'
        ]);

       $data=$request->all();
       
       @$innerImages = $data['innerImages'];
       
       $ids = [];
       foreach (@$innerImages as $key => $img) {
         $productImage = ProductImage::whereId($img['id']);
         $ids[] = $img['id'];
         if(@$img['image']){
                $file = $img['image'];
                $name = time().'.'.$file->extension();
               
                 \Storage::putFileAs(
                    'products', $file, $name
                );
                $image  =  '/storage/products/'.$name;  

                $img['image'] = $image;

                @unlink(public_path($productImage->pluck('image')));
          } 

          $productImage->update($img);

       }


       if($request->hasfile('inner.image'))
         {
            $images = [];
            foreach($request->file('inner.image') as $k => $file)
            {
                $name = time().$k.'.'.$file->extension();
                 \Storage::putFileAs(
                    'products', $file, $name
                );
                $images[] =  '/storage/products/'.$name;  
            }

            $data['inner']['image'] = $images;
         }

         $imageInput = @collect( $data['inner']['image']);
         $nameInput = @collect( $data['inner']['name']);

         $imagesData = [];

         $r = [];
      
        $imagesData = @$imageInput->map(function($image,$i) use ($r,$nameInput){
             $r['image'] = $image;
             $r['title'] = $nameInput[$i];
             return $r;
        });
        

        // return $data;
        $status = $product->fill($data)->save();
        $alredyImages = @$product->images()->whereNotIn('id',$ids)->pluck('id','image');

        foreach(@$alredyImages as $key => $aImg) {
            ProductImage::whereId($aImg)->delete();
            @unlink(public_path($key));
        }

        $productImages = ($productImages) ?? $product->images()->createMany($imagesData);

        if($status){
            request()->session()->flash('success','Product Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        @$images = $product->images()->pluck('image');

        foreach (@$images as $image) {
           @unlink(public_path($image));
        }
        $status=$product->delete();
        $product->images()->delete();
        
        if($status){
            request()->session()->flash('success','Product successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}

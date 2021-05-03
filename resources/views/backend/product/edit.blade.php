@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.update',$product->id)}}" enctype="multipart/form-data">
        @csrf 
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{$product->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{$product->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
<!-- 
        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{$product->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->


        
        <!-- <div class="form-group {{(($product->child_cat_id)? '' : 'd-none')}}" id="child_cat_div">
          <label for="child_cat_id">Sub Category</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Select any sub category--</option>
              
          </select>
        </div> -->

        <div class="form-group">
          <label for="price" class="col-form-label">Price(Per Sq Mtr.) <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price"  value="{{$product->price}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
       
        <div class="form-group image-container">
        @foreach($images as $k => $image)

            <div class="row element" id='div_{{$k+1}}'>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="form_name">Image *</label>
                        <input type='file' name="innerImages[{{$k}}][image]"  value="{{ $image->image }}" class="form-control" placeholder='Enter your skill' >
                      </br>
                        <img class="img" src="{{ asset($image->image) }}" />
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="form_email">Name *</label>
                        <input type='text'  name="innerImages[{{$k}}][title]" class="form-control"  value="{{ $image->title }}">
                        <input type='hidden'  name="innerImages[{{$k}}][id]" value="{{ $image->id }}">
                    </div>
                </div>
                <div class="col-sm-2" style="margin-top: 35px">
                   &nbsp;<span id='remove_{{$k+1}}' class='{{(($k == 0) ? "add" : "remove") }} col-sm-2'>{{(($k == 0) ? "+" : "x") }}</span>
                </div>
            </div>
  
        @endforeach
         </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($product->status=='active')? 'selected' : '')}}>Active</option>
            <option value="inactive" {{(($product->status=='inactive')? 'selected' : '')}}>Inactive</option>
        </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    
     // Add new element
         $(".add").click(function(){

          // Finding total number of elements added
          var total_element = $(".element").length;
         
          // last <div> with element class id
          var lastid = $(".element:last").attr("id");
          var split_id = lastid.split("_");
          var nextindex = Number(split_id[1]) + 1;

          var max = 15;
          // Check total number elements
          if(total_element < max ){
           // Adding new div container after last occurance of element class
           $(".element:last").after("<div class='row element' id='div_"+ nextindex +"'></div>");
         
           // Adding element to <div>
           $("#div_" + nextindex).append("<div class='col-sm-5'><div class='form-group'><input type='file' name='inner[image][]' class='form-control' placeholder='Enter your skill' value='' ></div> </div> <div class='col-sm-5'> <div class='form-group'> <input type='text' name='inner[name][]' class='form-control' placeholder='Enter Inner name' value=''> </div> </div><div class='col-sm-2'> &nbsp;<span class='remove col-sm-2' id='remove_" + nextindex + "' >x</span> </div>"); 
          }
         
         });

         // Remove element
         $('.image-container').on('click','.remove',function(){
         
          var id = this.id;
          var split_id = id.split("_");
          var deleteindex = split_id[1];

          // Remove <div> with id
          $("#div_" + deleteindex).remove();

         }); 

    });

</script>

<style type="text/css">
  .add,.remove{
      border: 2px solid;
      padding: 1px 10px;
      font-size: 23px;
    }
    .img{
      width: 100px;
    }
</style>
@endpush
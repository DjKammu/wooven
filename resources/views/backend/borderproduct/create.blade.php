@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Border Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('border-product.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="price" class="col-form-label">Price <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price"  value="{{old('price')}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

           <div class="form-group image-container">
            <div class="row element" id='div_1'>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="form_name">Image *</label>
                        <input type='file' name="inner[image][]"  class="form-control" placeholder='Enter your skill' value=""  >
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="form_email">Name *</label>
                        <input type='text' name="inner[name][]"  class="form-control" placeholder='Enter Border name' value="">
                    </div>
                </div>
                <div class="col-sm-2" style="margin-top: 35px">
                   &nbsp;<span class='add col-sm-2'>+</span>
                </div>
            </div>
        </div>
        
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
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
<style type="text/css">
  .add,.remove{
      border: 2px solid;
      padding: 1px 10px;
      font-size: 23px;
    }
</style>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
      $('#summary').summernote({
        placeholder: "Write short description.....",
          tabsize: 2,
          height: 100
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
           $("#div_" + nextindex).append("<div class='col-sm-5'><div class='form-group'><input type='file' name='inner[image][]' class='form-control' placeholder='Enter your skill' value='' ></div> </div> <div class='col-sm-5'> <div class='form-group'> <input type='text' name='inner[name][]' class='form-control' placeholder='Enter Border name' value=''> </div> </div><div class='col-sm-2'> &nbsp;<span class='remove col-sm-2' id='remove_" + nextindex + "' >x</span> </div>"); 
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

    // $(document).ready(function() {
    //   $('#description').summernote({
    //     placeholder: "Write detail description.....",
    //       tabsize: 2,
    //       height: 150
    //   });
    // });
    // $('select').selectpicker();

  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/admin/category/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Select sub category----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
@endpush
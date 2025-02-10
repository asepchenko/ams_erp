@extends('layouts.adminajax')
@section('content')

<div class="card">
    <div class="card-header">
        Upload Image
    </div>
    <div class="card-body">
        <form id="file-upload-form" class="uploader" action="{{ route("admin.tes.store") }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        @csrf
            <input type="file" id="file-input" name="image" /> <!-- multiple -->
            <span class="text-danger">{{ $errors->first('image') }}</span>
            <div id="thumb-output"></div>
            <br>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
 
$(document).ready(function(){
 $('#file-input').on('change', function(){ //on file input change
    //if (window.File &amp;&amp; window.FileReader &amp;&amp; window.FileList &amp;&amp; window.Blob) //check File API supported browser
    //{
         
        var data = $(this)[0].files; //this file data
         
        $.each(data, function(index, file){ //loop though each file
            if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                var fRead = new FileReader(); //new filereader
                fRead.onload = (function(file){ //trigger function on successful read
                return function(e) {
                    var img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element 
                    $('#thumb-output').append(img); //append image to output element
                };
                })(file);
                fRead.readAsDataURL(file); //URL representing the file's data.
            }
        });
         
    //}else{
     //   alert("Your browser doesn't support File API!"); //if File API is absent
   // }
 });
});
</script>
@parent
@endsection
@extends('layouts.master')
@section('title', 'Edit Topic - Quiz Admin')
@section('toolbar_title', 'Edit Topic')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')
    <form method="POST" action="{{route('topic_edit',['topicId'=>$topicId])}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleFormControlInput1" style="color: dodgerblue">Topic Title</label>
            <input type="text" class="form-control" name="title" id="title", required="true" value="{{$title}}">
        </div>
        <div class="row" style="margin: 10px 10px 15px 15px">
            <div class="form-group" >
                <label for="exampleFormControlFile1" style="color: dodgerblue">Topic Image</label>
                <input type="file" class="form-control-file" style="color: dodgerblue" name="image" id="image"accept="image/*" >
            </div>
            <div class="form-group col-md-3" >
                <img src="{{$topic->image }}" id="image_show" class="img-thumbnail" width="80"  />
                <input type="hidden" name="hidden_image" value="{{ $topic->image }}" />
            </div>
        </div>
        <div style="margin: 10px 10px 15px 15px">
            <button type="submit" class="btn btn-success">Update</button>
        </div>

    </form>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image_show').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#image").change(function(){
            readURL(this);
        });
    </script>
@endsection

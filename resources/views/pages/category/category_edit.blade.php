@extends('layouts.master')
@section('title','Edit Category')
@section('toolbar_title', 'Edit Category')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')
    <form method="POST" action="{{route('category_edit',['categoryId'=>$category['id']])}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleFormControlInput1" style="color: dodgerblue">Category Title</label>
            <input type="text" class="form-control" name="title" id="title", required="true" value="{{$category['title']}}">
        </div>


        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleSelectRounded0" style="color: dodgerblue">Choose topic</label>
            <select class="custom-select rounded-0" id="exampleSelectRounded0" name="topic_id">
                @foreach($topics as $topic)
                    @if($topic['id']==$category['topic_id'])
                        <option value="{{$topic['id']}}}" selected="selected" >{{$topic['title']}}</option>
                    @else
                        <option value="{{$topic['id']}}}" >{{$topic['title']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="row" style="margin: 10px 10px 15px 15px">
            <div class="form-group" >
                <label for="exampleFormControlFile1" style="color: dodgerblue">Category Image</label>
                <input type="file" class="form-control-file" style="color: dodgerblue" name="image" id="image"accept="image/*" >
            </div>
            <div class="form-group col-md-3" >
                <img src="{{$category->image }}" id="image_show" class="img-thumbnail" width="80"  />
                <input type="hidden" name="hidden_image" value="{{ $category->image }}" />
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

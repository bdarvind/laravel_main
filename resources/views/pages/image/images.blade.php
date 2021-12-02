@extends('layouts.master')
@section('title', 'Upload image')
@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (count($errors) > 0 )
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3 class="jumbotron">Upload the image</h3>
        <form method="post" action="{{url('preview-image-upload')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4"></div>
                <div class="form-group col-md-4">
                    <input type="file" name="profile_image" id="profile_image" onchange="loadPreview(this);" class="form-control">

                    <label for="profile_image"></label>
                    <img id="preview_img" src="https://w3adda.com/wp-content/uploads/2019/09/No_Image-128.png" class="" width="200" height="150"/>


                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-success" style="margin-top:10px">Upload Image</button>
                </div>
            </div>

        </form>
            <script>
                function loadPreview(input, id) {
                    id = id || '#preview_img';
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(id)
                                .attr('src', e.target.result)
                                .width(200)
                                .height(150);
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>

    </div>

@endsection

@extends('layouts.master')
@section('title','Create Category')
@section('toolbar_title', 'Create Category')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')
    <form method="POST" action="{{ route('category_add') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleFormControlInput1" style="color: dodgerblue">1. Category Title</label>
            <input type="text" class="form-control" name="title" id="title", required="true" >
        </div>
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleSelectRounded0" style="color: dodgerblue">2. Choose topic</label>
            <select class="custom-select rounded-0" id="exampleSelectRounded0" name="topic_id">
                @foreach($topics as $topic)
                    <option value={{$topic['id']}}>{{$topic['title']}}</option>
                @endforeach
            </select>
        </div>

        <div class="row" style="margin: 10px 10px 15px 15px">
            <div class="form-group" >
                <label for="exampleFormControlFile1" style="color: dodgerblue">3. Category Image</label>
                <input type="file" class="form-control-file" style="color: dodgerblue" name="image" id="image"accept="image/*" required="true">
            </div>
        </div>

        <div style="margin: 10px 10px 15px 15px">
            <button type="submit" class="btn btn-success">Create</button>
        </div>

    </form>
@endsection

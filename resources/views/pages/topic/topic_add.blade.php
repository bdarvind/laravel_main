@extends('layouts.master')
@section('title', 'Create Topic - Quiz Admin')
@section('toolbar_title', 'Create Topic')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')
    <form method="POST" action="{{ route('topic_add') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleFormControlInput1" style="color: dodgerblue">1. Topic Title</label>
            <input type="text" class="form-control" id="title" name="title" required="true" placeholder="Type the name of topic">
        </div>
        <div class="form-group" style="margin: 10px 10px 15px 15px">
            <label for="exampleFormControlFile1" style="color: dodgerblue">2. Topic Image</label>
            <input type="file" class="form-control-file" style="color: dodgerblue" required="true" name="image" id="image"accept="image/*" required="true">
        </div></br>
        <div style="margin: 10px 10px 15px 15px">
            <button type="submit" class="btn btn-success">Create Topic</button>
        </div>
    </form>
@endsection

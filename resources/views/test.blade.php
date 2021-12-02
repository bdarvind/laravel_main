@extends('layouts.master')

@section('title', 'Login - Quiz Admin')



@section('styles')
    {{--custom css item suggest search--}}
    <style>
        .autocomplete-group { padding: 2px 5px; }
    </style>
@stop


@section('content')
    <div class="main-content">
        <div class="top-page">
            This is a content
        <!-- this is content -->
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
    {{--jquery.autocomplete.js--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.4.10/jquery.autocomplete.min.js"></script>
    {{--quick defined--}}
    <script>
        $(function () {
            // your custom javascript
        });
    </script>
@stop

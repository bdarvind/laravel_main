
@extends('layouts.master')

@section('title', 'Topic Management')
@section('toolbar_title', 'Topic Management')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)

@section('style-libraries')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
@stop

@section('styles')
    {{--custom css item suggest search--}}
    <style>
        .autocomplete-group { padding: 2px 5px; }
    </style>
@stop
@section('content')


    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
    <div class="card-body">
        <a href="{{ route('topic_add') }}" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
            <span class="text">Create Topic</span>
        </a>

    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Topic List</h6>
        </div>
        <div class="card-body">
            @if(count($topics)>0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>

                        @foreach($topics as $topic)
                            <tr>
                                <td><img src="{{$topic->image}}"  width="60" height="50" /></td>
                                <td>{{$topic->title}}</td>
                                <td>{{$topic->created_at}}</td>
                                <td>{{$topic->updated_at}}</td>
                                <td>
                                    <a href="{{ route('topic_edit',['topicId'=>$topic['id']])}}" class="btn btn-info btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('topic_delete',['topicId'=>$topic['id']])}}" id="delete_btn" class="btn btn-danger btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete</span>
                                    </a>
                                </td>

                            </tr>

                            @endforeach

                            </tbody>
                    </table>

                </div>
            @else
                <div>
                    You didn't create any topic, Let's create !
                </div>
            @endif
        </div>
    </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#delete_btn").click(
                function (event)
                {
                    if (confirm("All categories, exams, questions relating to this topic will be deleted ! Are you sure to delete this topic ? "
                    )) {
                        return
                    } else {
                        event.preventDefault();
                        return
                    }
                }
            );

        });

    </script>
@endsection


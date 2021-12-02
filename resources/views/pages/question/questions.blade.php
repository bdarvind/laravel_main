
@extends('layouts.master')
@section('title', 'Question Management')
@section('toolbar_title', 'Question Management')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')

    <div class="card-body">
        <a href="{{ route('question_add') }}" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
            <span class="text">Create Question</span>
        </a>

    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Question List</h6>
        </div>
        <div class="card-body">
            @if(count($questions)>0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Topic</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>



                            @for($i = 0; $i< count($questions); $i++)
                            <tr>
                                <td>{{$questions[$i]['title']??""}}</td>
                                <td>{{$categories[$i]->title}}</td>
                                <td>{{$topics[$i]->title}}</td>
                                <td>
                                    <a href="{{ route('question_edit', ["questionId"=>$questions[$i]['id']??""])}}" class="btn btn-info btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                </td>
                                <td>


                                    <a href="{{ route('question_delete', ["questionId"=>$questions[$i]['id']??""])}}" id="delete_btn" class="btn btn-danger btn-icon-split btn-sm">
                                    <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete</span>
                                    </a>
                                </td>

                            </tr>

                            @endfor

                            </tbody>
                    </table>

                </div>
            @else
                <div>
                    You didn't create any question, Let's create !
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#delete_btn").click(
                function (event)
                {
                    if (confirm(" All exams including to this question will be updated !  Are you sure to delete the question ?"
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

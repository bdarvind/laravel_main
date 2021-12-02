
@extends('layouts.master')
@section('title', 'Category Management')
@section('toolbar_title', 'Category Management')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')

    <div class="card-body">
        <a href="{{ route('category_add') }}" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
            <span class="text">Create Category</span>
        </a>

    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
        </div>
        <div class="card-body">
            @if(count($categories)>0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Topic</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>

                        @for($i = 0; $i< count($categories); $i++)
                            <tr>
                                <td><img src="{{$categories[$i]->image}}"  width="60" height="50" /></td>
                                <td>{{$categories[$i]->title}}</td>
                                <td>{{$topics[$i]->title}}</td>
                                <td>{{$categories[$i]->created_at}}</td>
                                <td>{{$categories[$i]->updated_at}}</td>
                                <td>
                                    <a href="{{ route('category_edit', ["categoryId"=>$categories[$i]['id']??""])}}" class="btn btn-info btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('category_delete', ["categoryId"=>$categories[$i]['id']??""])}}" id="delete_btn" class="btn btn-danger btn-icon-split btn-sm">
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
                    There is no topic!
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#delete_btn").click(
                function (event)
                {
                    if (confirm("All exams, questions relating to this category will be updated or deleted ! Are you sure to delete this category ? "
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



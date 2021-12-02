@extends('layouts.master')
@section('title','Edit Question - Quiz Admin')
@section('toolbar_title', 'Edit Question')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')
    <form method="POST" action="{{ route('question_edit',['questionId'=>$question['id']]) }}">
        @csrf
        <div class="card shadow mb-4" style="margin: 10px 200px 15px 15px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">1. Choose topic</h6>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <select class="custom-select rounded-0" id="exampleSelectRounded0" name="topic_id" required="true">
                    @foreach($topics as $topic)
                        @if($question['topic_id']==$topic['id'])
                            <option value={{$topic['id']}} selected="selected">{{$topic['title']}}</option>
                        @else
                            <option value={{$topic['id']}}>{{$topic['title']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card shadow mb-4" style="margin: 10px 200px 15px 15px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">2. Choose Category</h6>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <select class="custom-select rounded-0" id="exampleSelectRounded0" name="category_id" id ="category_id" required="true">
                    @foreach($categories as $cate)
                        @if($cate['id']==$category['id'])
                            <option value="{{$cate['id']}}" selected>{{$cate['title']}}</option>
                        @else
                            <option value="{{$cate['id']}}">{{$cate['title']}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card shadow mb-4" style="margin: 10px 200px 15px 15px">
            <div class="card-header py-3" >
                <h6 class="m-0 font-weight-bold text-primary">3. Create the question</h6>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <label for="exampleSelectRounded0" style="color: dodgerblue">Type the title of the question</label>
                <textarea class="form-control" rows="3" name="title" id="title", required="true"  >{{$question['title']}}</textarea>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <label for="exampleSelectRounded0" style="color: dodgerblue">A Choice</label>
                <textarea class="form-control" rows="1" name="a_choice" id="a_choice", required="true" >{{$question['a_choice']}}</textarea>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <label for="exampleSelectRounded0" style="color: dodgerblue">B Choice</label>
                <textarea class="form-control" rows="1" name="b_choice" id="b_choice", required="true" >{{$question['b_choice']}}</textarea>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <label for="exampleSelectRounded0" style="color: dodgerblue">C Choice</label>
                <textarea class="form-control" rows="1" name="c_choice" id="c_choice", required="true" >{{$question['c_choice']}}</textarea>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <label for="exampleSelectRounded0" style="color: dodgerblue">D Choice</label>
                <textarea class="form-control" rows="1" name="d_choice" id="d_choice", required="true">{{$question['d_choice']}}</textarea>
            </div>
        </div>

        <div class="card shadow mb-4" style="margin: 10px 200px 15px 15px">
            <div class="card-header py-3" >
                <h6 class="m-0 font-weight-bold text-primary">4. Choose Right Answer</h6>
            </div>
            <div class="form-group" style="margin: 10px 10px 10px 10px">
                <select class="custom-select rounded-0" id="exampleSelectRounded0" name="right_answer" required="true">
                    @for($i = 1; $i<=4; $i++)
                        @switch ($i)
                            @case(1)
                            @if($question['right_answer']==$i){
                            <option value="1" selected="selected">A</option>
                            @else
                                <option value="1">A</option>
                            @endif
                            @break;

                            @case(2)
                            @if($question['right_answer']==$i){
                            <option value="2" selected="selected">B</option>
                            @else
                                <option value="2">B</option>
                            @endif
                            @break;

                            @case(3)
                            @if($question['right_answer']==$i){
                            <option value="3" selected="selected">C</option>
                            @else
                                <option value="3">C</option>
                            @endif
                            @break;

                            @case(4)
                            @if($question['right_answer']==$i){
                            <option value="4" selected="selected">D</option>
                            @else
                                <option value="4">D</option>
                            @endif
                            @break;
                        @endswitch
                    @endfor
                </select>
            </div>
        </div>

        <div style="margin: 10px 10px 15px 15px">
            <button type="submit" class="btn btn-success">Update</button>
        </div>

    </form>

    <script type="text/javascript">
        var url = "{{ url('/showCategoriesInTopic') }}";

        $("select[name='topic_id']").change(function(){
            var topic_id = $(this).val();
            var token = $("input[name='_token']").val();

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    topic_id: topic_id,
                    _token: token
                },
                success: function(data) {
                    $("select[name='category_id']").html('');
                    $.each(data, function(key, value){
                        $("select[name='category_id']").append(
                            "<option value=" + value.id + ">" + value.title + "</option>"
                        );
                    });
                }
            });
        });
    </script>


@endsection

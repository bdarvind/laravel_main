@extends('layouts.master')
@section('title', 'Edit Exam - Quiz Admin')
@section('toolbar_title', 'Edit Exam')
@section('name', Auth::user()->name )
@section('image', Auth::user()->image)
@section('content')

    <form method="POST"  action="{{ route('exam_edit',['examId' => $exam['id']]) }}">
        @csrf
        <div class="form-group" style="margin: 10px 100px 15px 15px">
            <label for="exampleFormControlInput1" style="color: dodgerblue">1. Exam Title</label>
            <input type="text" class="form-control" name="exam_title" id="exam_title", required="true" placeholder="Type the title of exam" value="{{$exam['title']}}" >
        </div>
        <div class="form-group" style="margin: 10px 100px 15px 15px">
            <label for="exampleSelectRounded0" style="color: dodgerblue">2. Choose topic</label>
            <select class="custom-select rounded-0" name="topic_id" id="topic_id">
                @foreach($topics as $top)
                    @if($top['id']==$topic['id'])
                        <option value="{{$top['id']}}" selected>{{$top['title']}}</option>
                    @else
                        <option value="{{$top['id']}}">{{$top['title']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin: 10px 100px 15px 15px">
            <label for="exampleSelectRounded0" style="color: dodgerblue">3. Choose category</label>
            <select class="custom-select rounded-0"  name="category_id" id="category_id">
                @foreach($categories as $cate)
                    @if($cate['id']==$category['id'])
                        <option value="{{$cate['id']}}" selected>{{$cate['title']}}</option>
                    @else
                        <option value="{{$cate['id']}}">{{$cate['title']}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="row" style="margin: 10px 85px 15px 3px">

            <!-- Choose questions for the exam -->
            <div class="col-xl-8 col-lg-7">
                <label for="exampleSelectRounded0" style="color: dodgerblue">4. Choose questions for the exam</label>
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->

                    <!-- Card Body -->
                    <div class="card-body" id="question_id">
                        <div  class="checkbox" >
                            @foreach($questions as $question)
                                <?php  $settedCheckbox = false;  ?>
                                @foreach($chosen_question_array as $chosen_question)
                                    @if($question['id']==$chosen_question)
                                        <input value="{{$question['id']}}" name="question_id" checked="checked" type="checkbox" onclick="clickCheckBox(this)" >  {{$question['title']}}<hr></br>
                                        <?php  $settedCheckbox = true;  ?>
                                        @break
                                    @endif
                                @endforeach
                                @if(!$settedCheckbox)
                                    <input value="{{$question['id']}}" name="question_id"  type="checkbox" onclick="clickCheckBox(this)" >  {{$question['title']}}<hr></br>
                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <!-- Chosen Questions -->
            <div class="col-xl-4 col-lg-5">
                <label for="exampleSelectRounded0" style="color: dodgerblue">Chosen Questions</label>
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                        <input type="hidden" id="chosen_question_list" name="chosen_question_list" value="{{implode(" ",$chosen_question_array)}}">
                        <input type="hidden" id="question_list" name="question_list">
                        <select id = "number_of_questions">
                            <option id="question_number" >{{count($chosen_questions)}}</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>


        <div style="margin: 10px 10px 15px 15px">
            <button type="submit" id="create_btn" class="btn btn-success">Update</button>
        </div>

    </form>




    <script type="text/javascript">
        var url_topic = "{{ url('/showCategoriesInTopic') }}";
        var url_category = "{{ url('/showQuestionsInCategory') }}";
        var url_send_questions = "{{ url('/getQuestionList') }}";
        var token = $("input[name='_token']").val();
        var question_list = (document.getElementById("chosen_question_list").value).split(" ");
        // save old topic
        var old_value = $('#topic_id :selected').val();
        $.when(
            $("select[name='topic_id']").change(function(){
                var topic_id = $(this).val();

                if(question_list.length!=0) {
                    if (confirm("If you change the topic, the question list will be cleared ! Are you sure to create the new exam for new topic ?"
                    )) {
                        // update old_value
                        old_value = $('#topic_id :selected').val();
                        question_list = [];
                        document.getElementById("question_list").value = "";
                        document.getElementById("question_number").innerHTML = "0";
                    } else {
                        // still use old topic
                        $('#topic_id').val(old_value);
                        return
                    }
                }
                old_value = $('#topic_id :selected').val();

                $.ajax({
                    url: url_topic,
                    method: 'POST',
                    data: {
                        topic_id: topic_id,
                        _token: token
                    },
                    success: function(data) {
                        var categoryId = 0;
                        $("select[name='category_id']").html('');
                        $.each(data, function(key, value){
                            if(key==0){
                                categoryId = value.id;
                                $("select[name='category_id']").append(
                                    "<option value=" + value.id + "selected"+ ">" + value.title + "</option>"
                                );
                            }
                            else {
                                $("select[name='category_id']").append(
                                    "<option value=" + value.id + ">" + value.title + "</option>"
                                );
                            }
                        });
                        if(categoryId!=0)
                        {

                            var token1 = $("input[name='_token']").val();

                            $.ajax({
                                url: url_category,
                                method: 'POST',
                                data: {
                                    category_id: categoryId,
                                    _token: token1
                                },
                                success: function(data) {
                                    $("#question_id").html("");
                                    var myDiv = document.getElementById("question_id");
                                    $.each(data, function(key, value){
                                        var checkBox = document.createElement("input");
                                        var label = document.createElement("label");
                                        checkBox.type = "checkbox";
                                        checkBox.value = value.id;
                                        checkBox.id = value.id;
                                        checkBox.onclick = function(){
                                            clickCheckBox(this);
                                        };
                                        checkBox.name='question_id';
                                        myDiv.appendChild(checkBox);
                                        myDiv.appendChild(label);
                                        label.appendChild(document.createTextNode( value.title));
                                        var br = document.createElement("br");
                                        myDiv.appendChild(br);
                                    });
                                }
                            });
                        }

                    }
                });
            }),

            $("#category_id").change(function(){
                var category_id = $(this).val();
                var token2 = $("input[name='_token']").val();
                $.ajax({
                    url: url_category,
                    method: 'POST',
                    data: {
                        category_id: category_id,
                        _token: token2
                    },

                    success: function(data) {
                        //$("select[name='question_id']").html('');
                        // create the necessary elements
                        $("#question_id").html("");
                        var myDiv = document.getElementById("question_id");

                        $.each(data, function(key, value){
                            var checkBox = document.createElement("input");
                            var label = document.createElement("label");
                            checkBox.type = "checkbox";
                            checkBox.value = value.id;
                            checkBox.name=value.id;

                            if(question_list.includes(parseInt(checkBox.value)))
                                checkBox.checked= true;

                            checkBox.id = value.id;
                            checkBox.onclick = function(){
                                clickCheckBox(this);
                            };
                            myDiv.appendChild(checkBox);
                            myDiv.appendChild(label);
                            label.appendChild(document.createTextNode( value.title));
                            var br = document.createElement("br");
                            myDiv.appendChild(br);

                        });
                    }
                });
            }),

        ).then(function(){});

        function clickCheckBox(id){
            var checkBox = document.getElementById(id);
            if (id.checked) {
                question_list.push(parseInt($(id).val()));
                var question_list_string = question_list.toString();
                document.getElementById("question_list").value = question_list_string;
                document.getElementById("question_number").innerHTML = question_list.length.toString();
            }
            else {
                var index = question_list.indexOf($(id).attr("id"));
                question_list.splice(index,1);
                var question_list_string = question_list.toString();
                document.getElementById("question_list").value = question_list_string;
                document.getElementById("question_number").innerHTML = question_list.length.toString();
            }
        }



        $(document).ready(function(){



            $("#create_btn").click(
                function (event)
                {
                    if(document.getElementById("question_list").value==="")
                    {
                        alert("Please add questions for the exam !");
                        event.preventDefault();
                    }

                    /*
                     $.ajax({
                         url: url_send_questions,
                         method: 'POST',
                         data: {
                             question_list: question_list_string,
                             _token: token
                         },
                         success:function(result){
                           alert(result);
                         }
                     });

                     */


                })
        })




    </script>

@endsection

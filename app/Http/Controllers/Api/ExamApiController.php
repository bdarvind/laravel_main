<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Topic;
use App\Exam;
use App\Question;
use App\ExamMark;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;




class ExamApiController extends Controller
{
    //
    public function search (Request $request){
        $data = $request->get('data');
        $data  = trim(preg_replace('/[\t|\s{2,}]/', '', $data));
        $topics = DB::table('topics')->where('topics.title','LIKE',"%$data%")
            ->get();

        $responses= array();

        if(!count($topics))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no topic with this search',
                'list'=>$responses

            ]);

        foreach ($topics as $topic)
        {
            $user = User::find($topic->creator_id);
            $response['id'] = $topic->id;
            $response['title'] = $topic->title;
            $response['creator'] = $user->name;
            $response['creator_id'] = $user->id;
            $response['image'] = $topic->image;
            $response['created_at'] = $topic->created_at;
            $response['updated_at'] = $topic->updated_at;
            array_push($responses, $response);
        }


        return response()->json([
            'status'=>'success',
            'message'=>'Search successfully',
            'list' => $responses
        ]);
    }


    public function searchExamFromUser($userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>'fail',
                'message'=>$validator->errors()->first(),
            ]);
        }

        $creator = User::where('email',$request->email)->first();



        $responses= array();

        if($creator==null)

            return response()->json([
                'status'=>'success',
                'message'=>'This email did not register yet',
                'list'=>$responses

            ]);

        $exams = Exam::where('creator_id', $creator->id)->get();


        if(!count($exams))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no exam with for this email',
                'list'=>$responses

            ]);


        foreach ($exams as $exam)
        {
            $topic = Topic::find($exam->topic_id);
            $exam_questions = array();
            $response['id'] = $exam->id;
            $response['title'] = $exam->title;
            $response['creator'] = $creator->name;
            $response['creator_id'] = $creator->id;
            $response['topic_id'] = $exam->topic_id;
            $response['topic_title'] = $topic->title;
            if($userId==0){
                $response['right_questions'] = 0;
            }
            else {
                $examMark= ExamMark::where([
                    ['exam_id',$exam->id],
                    ['user_id',$userId],

                    ])->get()->first();
                if($examMark==null)
                $response['right_questions'] = 0;
                else $response['right_questions'] = $examMark->right_questions;
            }
            $response['created_at'] = $exam->created_at;
            $response['updated_at'] = $exam->updated_at;
            $question_list = $exam->question_list;
            $questionIds = explode(',' ,$question_list);
            foreach($questionIds as $questionId)
            {
                $question= Question::find($questionId);
                array_push($exam_questions, $question);

            }
            $response['questions'] = $exam_questions;


            array_push($responses, $response);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Search successfully',
            'list' => $responses
        ]);

    }

    public function getExamFromTopic($topicId)
    {

        $responses= array();
        $exams = Exam::where('topic_id', $topicId)->get();


        if(!count($exams))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no any exam for this topic',
                'list'=>$responses

            ]);

        $user = User::find($exams[0]->creator_id);


        foreach ($exams as $exam)
        {
            $exam_questions = array();
            $response['id'] = $exam->id;
            $response['title'] = $exam->title;
            $response['creator'] = $user->name;
            $response['creator_id'] = $user->id;
            $response['topic_id'] = $exam->topic_id;
            $response['created_at'] = $exam->created_at;
            $response['updated_at'] = $exam->updated_at;
            $question_list = $exam->question_list;
            $questionIds = explode(',' ,$question_list);
            for($i=0; $i< count($questionIds); $i++)
            {
                $question= Question::find($questionIds[$i]);
                array_push($exam_questions, $question);

            }
            $response['questions'] = $exam_questions;


            array_push($responses, $response);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Search successfully',
            'list' => $responses
        ]);

    }

    public function getExamById($examId)
    {
        $exam = Exam::find($examId);
        if($exam==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get Exam Error',
                'data'=>[]]);
        $exam_questions = array();

        $response['id'] = $exam->id;
        $response['title'] = $exam->title;
        $response['creator_id'] = $exam->creator_id;
        $response['topic_id'] = $exam->topic_id;
        $response['created_at'] = $exam->created_at;
        $response['updated_at'] = $exam->updated_at;
        $question_list = $exam->question_list;
        $questionIds = explode(',' ,$question_list);
        for($i=0; $i< count($questionIds); $i++)
        {
            $question= Question::find($questionIds[$i]);
            array_push($exam_questions, $question);

        }
        $response['questions'] = $exam_questions;

        return response()->json([
            "status"=>"success",
            'message'=>'Get Exam Successfully',
            'data'=>$response]);
    }

    public function getExamByCategory($userId, $categoryId)
    {
        $category = Category::find($categoryId);
        $topicExams = Exam::where('topic_id', $category->topic_id)->get();
        $exams = array();
        foreach ($topicExams as $exam)
        {
            $question_list = $exam->question_list;
            $qIds = explode(',' ,$question_list);
            $isBelongCategory = 0;
            for($i=0; $i< count($qIds); $i++)
            {

                $question= Question::find($qIds[$i]);

                if($question->category_id==$categoryId){

                    $isBelongCategory= 1;
                    break;
                }

            }
            if($isBelongCategory) array_push($exams, $exam);

        }
        $responses= array();

        if(!count($exams))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no exam with for this category',
                'list'=>$responses

            ]);



        foreach ($exams as $exam)
        {
            $exam_questions = array();
            $creator = User::find($exam->creator_id);
            $topic = Topic::find($exam->topic_id);
            $examMark= ExamMark::where([
                ['exam_id',$exam->id],
                ['user_id',$userId],

            ])->get()->first();
            if($examMark==null)
                $response['right_questions'] = 0;
            else $response['right_questions'] = $examMark->right_questions;

            $response['id'] = $exam->id;
            $response['title'] = $exam->title;
            $response['creator'] = $creator->name;
            $response['creator_id'] = $exam->creator_id;
            $response['topic_id'] = $exam->topic_id;
            $response['topic_title'] = $topic->title;
            $response['created_at'] = $exam->created_at;
            $response['updated_at'] = $exam->updated_at;
            $question_list = $exam->question_list;
            $questionIds = explode(',' ,$question_list);
            for($i=0; $i< count($questionIds); $i++)
            {
                $question= Question::find($questionIds[$i]);
                array_push($exam_questions, $question);

            }
            $response['questions'] = $exam_questions;


            array_push($responses, $response);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Search successfully',
            'list' => $responses
        ]);
    }


    public function getTestedExamByUserId($userId){
        $examMarks = ExamMark::where('user_id', $userId)->get();
        $responses = array();

        if(!count($examMarks))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no any tested exam for this user',
                'list'=>$responses
            ]);

        foreach ($examMarks as $examMark)
        {
            $exam = Exam::find($examMark->exam_id);
            $topic = Topic::find($exam->topic_id);
            $creator = User::find($exam->creator_id);
            $response['exam_id']=$examMark->exam_id;
            $response['creator_id']=$exam->creator_id;
            $response['creator']=$creator->name;
            $response['exam_title']=$exam->title;
            $response['topic_title']=$topic->title;
            $response['questions_list']=$exam->question_list;
            $response['right_questions']=$examMark->right_questions;
            array_push($responses,$response);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Get Tested Exam List Successfully',
            'list'=>$responses
        ]);


    }


}

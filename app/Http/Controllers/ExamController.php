<?php

namespace App\Http\Controllers;

use App\Category;
use App\ExamMark;
use App\Question;
use App\Topic;
use App\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $exam;
    protected $question_list;

    public function __construct()
    {
        $this->exam = new Exam();
        $this->question_list ="";

    }

    public function getQuestionList(Request $request)
    {
        if ($request->ajax()) {
            $this->question_list = $request->question_list;
            return($this->question_list);
        }
    }

    public function showCategoriesInTopic(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::where('topic_id',$request->topic_id)->select('id', 'title')->get();

            return response()->json($categories);
        }
    }

    public function accessExamAdd()
    {
        $topics = Topic:: where(
            'creator_id',Auth::id()
        )->get();
        $categories = Category::where('topic_id',$topics[0]['id'])->get();
        $category = $categories->first();
        $questions = Question::where('category_id',$category['id'])->get();

        return view('pages.exam.exam_add', [
            'topic'=>$topics[0],
            'topics'=>$topics,
            'categories'=>$categories,
            'category'=>$category,
            'questions'=>$questions,
        ]);

    }



    public function accessEditExam($examId)
    {
        $exam = Exam::find($examId);
        $topic = Topic::find($exam['topic_id']);
        $topics = Topic::where('creator_id',$exam['creator_id'])->get();
        $question_list = $exam->question_list;
        $chosen_question_array = explode(',' ,$question_list);
        $chosen_questions = array();
        foreach ($chosen_question_array as $question)
        {
            $question = Question::find($question);
            array_push($chosen_questions, $question);
        }
        $category = Category::find($chosen_questions[0]['category_id']);
        $categories = Category::where("topic_id", $category['topic_id'])->get();
        $questions = Question::where("category_id", $category['id'])->get();

        return view('pages.exam.exam_edit',[
            'exam'=>$exam,
            'topic'=>$topic,
            'topics'=>$topics,
            'categories'=>$categories,
            'category'=>$category,
            'questions'=>$questions,
                "chosen_questions"=>$chosen_questions,
                "chosen_question_array"=>$chosen_question_array]
        );
    }

    public function updateExam($examId, Request $request)
    {
        $new_exam = Exam::find($examId);
        $new_exam->title = $request->exam_title;
        //$new_exam->creator_id= Auth::id();
        $new_exam->topic_id = $request->topic_id;
        $new_exam->question_list = $request->question_list;
        $new_exam->save();

        return redirect() -> route("exams");

    }

    //
    public function loadAll (){
        $exams = Exam::where('creator_id',Auth::id())
            -> get();
        $topics= array();
        foreach ($exams as $exam)
        {
            $topic = Topic:: find($exam['topic_id']);
            array_push($topics, $topic);
        }
        return view('pages.exam.exams',
            [
                'exams'=>$exams,
                'topics'=>$topics
            ]
        );
    }


    public function addExam(Request $request)
    {
        $this->exam->title= $request->exam_title;
        $this->exam->creator_id= Auth::id();
        $this->exam->topic_id= $request->topic_id;
        $this->exam->question_list = $request->question_list;
        $this->exam->save();
        return redirect() -> route("exams");
    }

    public function deleteExam($examId)
    {
        $exam = Exam::find($examId);
        $examMarks = ExamMark::where('exam_id', $exam->id)->delete();
        $exam->delete();
        return redirect('exams');
    }



}

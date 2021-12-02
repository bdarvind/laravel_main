<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exam;
use App\Question;
use App\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    protected $question;

    public function __construct()
    {
        $this->question = new Question();
    }

    public function showQuestionsInCategory(Request $request)
    {
        if ($request->ajax()) {
            $questions = Question::where('category_id',$request->category_id)->select('id', 'title')->get();
            return response()->json($questions);
        }
    }

    public function showQuestionsInTopic(Request $request)
    {
        if ($request->ajax()) {
            $questions = Question::where('topic_id',$request->topic_id)->select('id', 'title')->get();
            return response()->json($questions);
        }
    }

    public function accessQuestionAdd()
    {


        $topics = Topic:: where(
            'creator_id',Auth::id()
        )->get();

        $categories = Category:: where(
            'topic_id',$topics[0]['id']
        )->get();



        return view('pages.question.question_add', ['topics'=>$topics, 'categories'=>$categories]);
    }

    public function accessEditQuestion($questionId)
    {
        $question = Question::find($questionId);
        $category =  Category::find($question['category_id']);
        $topic =  Topic::find($question['topic_id']);
        $categories = Category::where('topic_id',$topic['id'])->get();
        $topics = Topic::where('creator_id',$question['creator_id'])->get();
        return view('pages.question.question_edit',
            ['question'=> $question,
            'topic'=>$topic,
            'category'=>$category,
            'categories'=>$categories,
            'topics'=>$topics] );
    }

    public function updateQuestion($questionId, Request $request)
    {
        $new_question = Question::find($questionId);
        $new_question->title = $request->title;
        $new_question->a_choice = $request->a_choice;
        $new_question->b_choice = $request->b_choice;
        $new_question->c_choice = $request->c_choice;
        $new_question->d_choice = $request->d_choice;
        $new_question->right_answer = $request->right_answer;
        $new_question->topic_id = $request->topic_id;
        $new_question->category_id = $request->category_id;
        $new_question->save();

        return redirect('questions');
    }

    //
    public function loadAll (){

        $questions = Question::where('creator_id',Auth::id())
            -> get();
        $categories= array();
        $topics= array();
        foreach ($questions as $question)
        {
            $category = Category:: where(
                'id',$question['category_id']
            )->first();
            array_push($categories, $category);

            $topic = Topic:: where(
                'id',$question['topic_id']
            )->first();
            array_push($topics, $topic);
        }
        return view('pages.question.questions',
            [
                'categories'=>$categories,
                'topics'=>$topics,
                'questions'=>$questions
            ]
        );
    }


    public function addQuestion(Request $request)
    {
        $this->question->title= $request->title;
        $this->question->a_choice= $request->a_choice;
        $this->question->b_choice= $request->b_choice;
        $this->question->c_choice= $request->c_choice;
        $this->question->d_choice= $request->d_choice;
        $this->question->right_answer = $request->right_answer;
        $this->question->category_id = $request->category_id;
        $this->question->topic_id= $request->topic_id;
        $this->question->creator_id= Auth::id();
        $this->question->save();
        return redirect('questions');
    }

    public function deleteQuestion($questionId)
    {
        $question = Question::find($questionId);
        $topic = Topic::find($question->topic_id);
        $exams = Exam::where('topic_id', $topic->id)->get();
        foreach ($exams as $exam)
        {
            $question_list = explode(',', $exam->question_list);
            if (($key = array_search($question->id, $question_list)) !== false) {
                unset($question_list[$key]);
                $exam->question_list = implode(",", $question_list);
                $exam->save();
            }

        }

        $question->delete();
        return redirect('questions');
    }



}

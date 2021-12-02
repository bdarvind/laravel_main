<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\User;
use App\Category;
use App\Exam;
use App\Topic;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $topics = Topic::where("creator_id", $user->id)->get();
        $categories = Category::where("creator_id", $user->id)->get();
        $questions = Question::where("creator_id", $user->id)->get();
        $exams = Exam::where("creator_id", $user->id)->get();


        return view('home', [
            "user"=>$user,
            "topics"=>$topics,
            "categories"=>$categories,
            "questions"=>$questions,
            "exams"=>$exams
        ]);
    }

    public function showUserInToolbar(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            if($user==null)
                return response()->json(
                    [
                        'name'=>"Not login yet",
                        'image'=>"/images/default_icon.png"
                    ]
                );

            return response()->json($user);
        }
    }




}

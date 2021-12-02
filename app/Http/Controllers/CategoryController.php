<?php

namespace App\Http\Controllers;

use App\Category;
use App\Exam;
use App\Question;
use App\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        $this->category = new Category();

    }

    public function showCategories($topicId)
    {
        $categories = Category:: where(
            'topic_id',$topicId
        )->get();


        return view('pages.exam.exam_add_question', ['categories'=>$categories]);
    }


    public function showCategoriesInTopic(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::where('topic_id',$request->topic_id)->select('id', 'title')->get();

            return response()->json($categories);
        }
    }

    public function accessCategoryAdd()
    {


            $topics = Topic:: where(
                'creator_id',Auth::id()
            )->get();


        return view('pages.category.category_add', ['topics'=>$topics]);
    }

    public function accessEditCategory($categoryId)
    {
        $category = Category::find($categoryId);
        $topics = Topic::where('creator_id',$category['creator_id'])->get();
        return view('pages.category.category_edit',['category'=>$category,'topics'=>$topics] );
    }

    public function updateCategory($categoryId, Request $request)
    {
        $image_name = $request->hidden_image;
        $files = $request->file('image');
        $new_category = Category::find($categoryId);
        if($files != '')  // here is the if part when you dont want to update the image required
        {
            request()->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $destinationPath = public_path('/images/');
            //code for remove old file
            if($new_category->image != ''  && $new_category->image != null){
                $old_image = $destinationPath.basename($new_category->image);
                unlink($old_image);
            }
            // upload path
            $profileImage = url("/images/".date('YmdHis') . "." . $files->getClientOriginalExtension());
            $image_name = $profileImage;
            $files->move($destinationPath, $profileImage);
        }

        $new_category = Category::find($categoryId);
        $new_category->title = $request->title;
        $new_category->topic_id = $request->topic_id;
        $new_category->image = $image_name;
        $new_category->save();

        return redirect('categories');
    }

    //
    public function loadAll (){

        $categories = Category::where('creator_id',Auth::id())
            -> get();
        $topics= array();
        foreach ($categories as $category)
        {
            $topic = Topic:: where(
                'id',$category['topic_id']
                )->first();
            array_push($topics, $topic);
        }
        return view('pages.category.categories',
            [
                'categories'=>$categories,
                'topics'=>$topics
            ]
        );
    }


    public function addCategory(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $files = $request->file('image');
        $destinationPath = public_path('/images/'); // upload path
        // Upload Orginal Image
        $profileImage = url("/images/".date('YmdHis') . "." . $files->getClientOriginalExtension());
        $files->move($destinationPath, $profileImage);


        $this->category->title= $request->title;
        $this->category->creator_id= Auth::id();
        $this->category->topic_id= $request->topic_id;
        $this->category->image= $profileImage;

        $this->category->save();
        return redirect('categories');
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);
        $destinationPath = public_path('/images/');

        //code for remove old file
        if($category->image != ''  && $category->image != null){
            $old_image = $destinationPath.basename($category->image);
            unlink($old_image);
        }
        $questions = Question::where('category_id',$categoryId)->get();
        foreach ($questions as $question)
        {
            $exams = Exam::where('topic_id', $question->topic_id)->get();
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
        }
        $category->delete();
        return redirect('categories');
    }



}

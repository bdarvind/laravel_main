<?php

namespace App\Http\Controllers;


use App\Question;
use App\User;
use App\Category;
use App\Exam;
use Illuminate\Http\Request;
use App\Topic;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class TopicController extends Controller
{

    protected $topic;

    public function __construct()
    {
        $this->topic = new Topic();
        $this->topic->creator_id = Auth::id();
    }


    public function accessTopicAdd()
    {
        return view('pages.topic.topic_add');
    }

    public function accessEditTopic($topicId)
    {
        $topic = Topic::find($topicId);

        return view('pages.topic.topic_edit',['topicId'=>$topicId,'title'=>$topic->title,'topic'=>$topic] );
    }

    public function updateTopic($topicId, Request $request)
    {
        $image_name = $request->hidden_image;
        $files = $request->file('image');
        $new_topic = Topic::find($topicId);
        if($files != '')  // here is the if part when you dont want to update the image required
        {
            request()->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $destinationPath = public_path('/images/');
            //code for remove old file
            if($new_topic->image != ''  && $new_topic->image != null){
                $old_image = $destinationPath.basename($new_topic->image);
                unlink($old_image);
            }
            // upload path
            $profileImage = url("/images/".date('YmdHis') . "." . $files->getClientOriginalExtension());
            $image_name = $profileImage;
            $files->move($destinationPath, $profileImage);
        }

        $new_topic->title = $request->title;
        $new_topic->image = $image_name;

        $new_topic->save();

        return redirect('topics')->with('Success', 'You updated successfully');;
    }

    //
    public function loadAll (){

            $topics = Topic::where('creator_id',Auth::id())
                       -> get();
            return view('pages.topic.topics',
            ['topics'=>$topics]
            );
    }


    public function addTopic(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $files = $request->file('image');
        $destinationPath = public_path('/images/'); // upload path
        // Upload Orginal Image
        $profileImage = url("/images/".date('YmdHis') . "." . $files->getClientOriginalExtension());
        //$profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
        $files->move($destinationPath, $profileImage);
        $insert['image'] = "$profileImage";

        $this->topic->title= $request->title;
        $this->topic->creator_id= Auth::id();
        $this->topic->image= $profileImage;

        $this->topic->save();
        return redirect('topics')->with('Success', 'You created the topic successfully');;
    }

    public function deleteTopic($topicId)
    {
        $topic = Topic::find($topicId);
        $destinationPath = public_path('/images/');

        //code for remove old file
        if($topic->image != ''  && $topic->image != null){
            $old_image = $destinationPath.basename($topic->image);
            unlink($old_image);
        }

        $questions = Question::where('topic_id', $topicId)->delete();
        $exams = Exam::where('topic_id', $topicId)->delete();
        $categories = Category::where('topic_id', $topicId)->delete();

        $topic->delete();
        return redirect('topics')->with('Success', 'You deleted successfully');
    }




}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;




class TopicApiController extends Controller
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


    public function searchTopicFromUser(Request $request)
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

        $user = User::where('email',$request->email)->first();


        $responses= array();

        if($user==null)

            return response()->json([
                'status'=>'success',
                'message'=>'This email did not register yet',
                'list'=>$responses

            ]);

        $topics = Topic::where('creator_id', $user->id)->get();


        if(!count($topics))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no topic with for this email',
                'list'=>$responses

            ]);

        foreach ($topics as $topic)
        {
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

    public function getTopicById($topicId)
    {
        $topic = Topic::find($topicId);
        if($topic==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get Topic Error',
                'data'=>[]]);

        return response()->json([
            "status"=>"success",
            'message'=>'Get Topic Successfully',
            'data'=>$topic]);
    }




}

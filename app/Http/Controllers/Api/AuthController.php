<?php

namespace App\Http\Controllers\Api;

use App\Exam;
use App\Http\Controllers\Controller;
use App\Question;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        //Validate requested data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'retype_password' => 'required|same:password',
        ]);


        if($validator->fails()){
            return response()->json([
                'status'=>'fail',
                'message'=>$validator->errors()->first(),
                'error'=>$validator->errors()->toArray(),
            ]);

        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['image'] =  url('/images/default_icon.png');
        $input['following'] = implode(" ",[]);
        $input['followers'] = implode(" ",[]);
        $user = User::create($input);

        $token=  $user->createToken('quizApi')->accessToken;

        return response()->json([
            'status'=>'success',
            'message'=> "User registered successfully.",
            'token'=>$token,
            "data"=>$user
        ]);
    }

    public function login (Request $request)
    {
        //Validate requested data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required',

        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'fail',
                'message'=>$validator->errors()->first(),
                'error'=>$validator->errors()->toArray(),
            ]);
        }
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'status'=>'fail',
                'message'=>'Password or Email is incorrect !!',
            ], 401);
        }

        $user= $request->user();
        $token = $user->createToken('quizApi')->accessToken;

        return response()->json([
            'status'=>'success',
            'message'=>'Logined Successfully',
            'token'=>$token,
            'data'=>$user
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => 'success',
            'message'=> "Logged out successfully!"
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'data'=>$request->user()]);
    }

    public function getUserById($userId)
    {
        $user = User::find($userId);
        if($user==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get User Error',
                'data'=>[]]);

        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'data'=>$user]);
    }

    public function updateProfile($userId, Request $request)
    {
        $user = User::find($userId);
        if (!$user) {
            return Response::json([
                'statuse'=> 'fail',
                'message' => 'This user not available'], 404);
        }
        //Validate requested data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes',
            'nationality' => 'sometimes',
        ]);


        if($validator->fails()){
            return response()->json([
                'status'=>'fail',
                'message'=>$validator->errors()->first(),
                'error'=>$validator->errors()->toArray(),
            ]);

        }
        $files = $request->file('image');
        $image_name = $user->image;
        if($files != '')  // here is the if part when you dont want to update the image required
        {
            request()->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500000',
            ]);
            if (!$files->isValid()) {
                return response()->json([
                    'status'=>'fail',
                    'message' => 'Please upload an image'
                ], 400);
            }
            $destinationPath = public_path('/images/');
            //code for remove old file
            if($user->image != ''  && $user->image != null){
                if(basename($user->image)!="default_icon.png") {
                    $old_image = $destinationPath . basename($user->image);
                    unlink($old_image);
                }
            }
            // upload path
            $profileImage = url("/images/".date('YmdHis') . "." . $files->getClientOriginalExtension());
            $image_name = $profileImage;
            $files->move($destinationPath, $profileImage);
        }

        $user->name = $request->name;
        $user->image = $image_name;
        $user->nationality = $request->nationality;
        $user->save();



        return response()->json([
            'status'=>'success',
            'message'=> "Updated User successfully.",
            "data"=>$user
        ]);
    }

    public function followUser($accountId,$userId)
    {
        // user who will be added
        $user = User::find($userId);
        // current account
        $current_user = User::find($accountId);
        // follower list of $user
        $followerIds = explode(',' ,$user->followers);
        $followerIds = array_filter($followerIds);
        // following list of current_user
        $followingIds = explode(',' ,$current_user->following);
        $followingIds = array_filter($followingIds);
        // add current user to followers list of user who will be added
       // array_push($followerIds,$current_user->id);
       // array_push($followingIds,$user->id);

        if (!in_array($current_user->id, $followerIds))
        {
            array_push($followerIds,$current_user->id);
        }
        // add user to following list of current user
        if (!in_array($user->id, $followingIds))
        {
            array_push($followingIds,$user->id);
        }

        $user->followers = implode(",",$followerIds);
        $current_user->following = implode(",",$followingIds);

        $user->save();
        $current_user->save();

        return response()->json([
            'status'=>'success',
            'message'=> "followed user successfully.",
            "data"=>$current_user
        ]);
    }

    public function unfollowUser($accountId,$userId)
    {
        // user who will be added
        $user = User::find($userId);
        // current account
        $current_user = User::find($accountId);
        // follower list of $user
        $followerIds = explode(',' ,$user->followers);
        $followerIds = array_filter($followerIds);
        // following list of current_user
        $followingIds = explode(',' ,$current_user->following);
        $followingIds = array_filter($followingIds);
        // remove current user from followers list of user who will be added
        if (($key1 = array_search($current_user->id, $followerIds)) !== false) {
            unset($followerIds[$key1]);
        }
        // remove to following list of current user
        if (($key2 = array_search($user->id, $followingIds)) !== false) {
            unset($followingIds[$key2]);
        }
        $user->followers = implode(",",$followerIds);
        $current_user->following = implode(",",$followingIds);
        $user->save();
        $current_user->save();

        return response()->json([
            'status'=>'success',
            'message'=> "unfollowed user successfully.",
            "data"=>$current_user
        ]);
    }

    public function searchUserFromEmail(Request $request)
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




        return response()->json([
            'status'=>'success',
            'message'=>'Get User Successfully',
            'data'=>$user
        ]);



    }

    public function getFollowingUser($userId)
    {
        $user = User::find($userId);
        if($user==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get User Error',
                'data'=>[]]);
        $following = explode(',' ,$user->following);
        $following_arr = array();
        foreach ($following as $userId)
        {
            $following_user = User::find($userId);
            if($following_user!=null)
            array_push($following_arr,$following_user );
        }
        $data['following_number'] = count($following_arr);
        $data['following'] = $following_arr;

        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'data'=>$data]);
    }

    public function getFollowers($userId)
    {
        $user = User::find($userId);
        if($user==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get User Error',
                'data'=>[]]);
        $followers = explode(',' ,$user->followers);
        $followers_arr = array();
        foreach ($followers as $userId)
        {
            $followers_user = User::find($userId);
            if($followers_user!=null)
            array_push($followers_arr, $followers_user);
        }
        $data['followers_number'] = count($followers_arr);
        $data['followers'] = $followers_arr;

        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'data'=>$data]);
    }

    public function getLatestFollowingExams($userId)
    {
        $user = User::find($userId);
        if($user==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get User Error',
                'data'=>[]]);
        $following = explode(',' ,$user->following);
        $responses= array();
        foreach ($following as $userId)
        {
            $following_user = User::find($userId);
            if($following_user!=null) {
                $exam = Exam::where('creator_id',$userId )->get()->last();
                $topic = Topic::find($exam->topic_id);
                $exam_questions = array();
                $response['creator_data'] = $following_user;
                $response['id'] = $exam->id;
                $response['title'] = $exam->title;
                $response['creator'] = $following_user->name;
                $response['topic_title'] = $topic->title;
                $response['creator_id'] = $following_user->id;
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
        }

        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'list'=>$responses]);
    }

    public function getLatestFollowingTopics($userId)
    {
        $user = User::find($userId);
        if($user==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get User Error',
                'data'=>[]]);
        $following = explode(',' ,$user->following);
        $responses= array();
        foreach ($following as $userId)
        {
            $following_user = User::find($userId);
            if($following_user!=null) {
                $topic = Topic::where('creator_id',$userId )->get()->last();
                $response['creator_data'] = $following_user;
                $response['id'] = $topic->id;
                $response['title'] = $topic->title;
                $response['creator_id'] = $topic->creator_id;
                $response['creator'] = $following_user->name;
                $response['image'] = $topic->image;
                $response['created_at'] = $topic->created_at;
                $response['updated_at'] = $topic->updated_at;
                array_push($responses, $response);
            }
        }

        return response()->json([
            "status"=>"success",
            'message'=>'Get Information Successfully',
            'list'=>$responses]);
    }

}

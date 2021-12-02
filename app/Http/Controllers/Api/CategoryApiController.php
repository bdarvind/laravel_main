<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Topic;
use App\Category;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\DB;




class CategoryApiController extends Controller
{

    public function getCategoryFromTopic($topicId)
    {

        $responses= array();
        $categories = Category::where('topic_id', $topicId)->get();


        if(!count($categories))

            return response()->json([
                'status'=>'success',
                'message'=>'There is no category for this topic',
                'list'=>$responses

            ]);

        $user = User::find($categories[0]->creator_id);

        foreach ($categories as $category)
        {

            $response['id'] = $category->id;
            $response['title'] = $category->title;
            $response['creator'] = $user->name;
            $response['creator_id'] = $user->id;
            $response['topic_id'] = $category->topic_id;
            $response['image'] = $category->image;
            $response['created_at'] = $category->created_at;
            $response['updated_at'] = $category->updated_at;
            array_push($responses, $response);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Get category successfully',
            'list' => $responses
        ]);

    }

    public function getCategoryById($categoryId)
    {
        $category = Category::find($categoryId);
        if($category==null)
            return response()->json([
                "status"=>"fail",
                'message'=>'Get Category Error',
                'data'=>[]]);

        return response()->json([
            "status"=>"success",
            'message'=>'Get Category Successfully',
            'data'=>$category]);
    }




}

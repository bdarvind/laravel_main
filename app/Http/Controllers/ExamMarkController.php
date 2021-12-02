<?php

namespace App\Http\Controllers;

use App\ExamMark;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Exam;
use Validator;

class ExamMarkController extends Controller
{
    //

    public function addMark(Request $request){
        //Validate requested data
        $validator = Validator::make($request->all(), [
            'right_questions' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>'fail',
                'message'=>$validator->errors()->first(),
            ]);

        }

        $examMarkChecked = ExamMark::where([
            ['exam_id',$request->examId],
            ["user_id", $request->userId]
        ])->first();
        if($examMarkChecked==null){
            $examMark = new ExamMark();
            $examMark['exam_id']=$request->examId;
            $examMark['user_id']=$request->userId;
            $examMark['right_questions']=$request->right_questions;
            $examMark->save();
            return response()->json([
                "status"=>"success",
                "message"=>"Add mark for this exam successfully",

            ]);
        }
        else {
            $examMarkChecked['right_questions']=$request->right_questions;
            $examMarkChecked->save();
            return response()->json([
                "status"=>"success",
                "message"=>"Updated for this exam successfully",

            ]);
        }






    }
}

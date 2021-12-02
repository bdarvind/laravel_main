<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use App\Photo;

class PhotoController extends Controller
{

    public function index()
    {
        return view('pages.image.images');
    }

    public function store(Request $request)
    {
        request()->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($files = $request->file('profile_image')) {
            // Define upload path
            $destinationPath = public_path('/images/'); // upload path
            // Upload Orginal Image
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);

            $insert['image'] = "$profileImage";
            // Save In Database
            $imagemodel= new Photo();
            $imagemodel->title="$profileImage";
            $imagemodel->save();
        }
        return back()->with('success', 'Image Upload successfully');

    }

    public function updateUserImage(Request $request, $userId){

        $user  = User::find($userId);

        if($request->file != ''){
            $path = public_path().'/uploads/images/';

            //code for remove old file
            if($user->file != ''  && $user->file != null){
                $file_old = $path.$user->file;
                unlink($file_old);
            }

            //upload new file
            $file = $request->file;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            //for update in table
            $user->update(['file' => $filename]);
        }
    }



}

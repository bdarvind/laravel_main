<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Hash;

class RegisterController extends Controller
{
    public function register()
    {

        return view('auth.register');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nationality' => "",
            'image'=> url('/images/default_icon.png'),
            'following'=>implode(" ",[]),
            'followers'=>implode(" ",[]),
            'password' => Hash::make($request->password),
        ]);

        return redirect('login');
    }

}

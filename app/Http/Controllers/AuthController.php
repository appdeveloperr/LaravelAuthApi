<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'username'=>'required',
            'password' => 'required',
            'phone' =>'required',
        ]);

        $user = new  User();
        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->save();
        $user = User::where('id',$user->id)->first();
        $accessToken = $user->createToken('authToken')->accessToken;
        if ($user){
            $user['accessToken'] = $accessToken;
            return $user;
        }


    }
    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!auth()->attempt(['email'=>$request->email,'password'=>$request->password,'type'=>'customer'])){
            return "Invalid email or password";
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $user = auth()->user();
        $user['accessToken'] = $accessToken;
        return $user;

    }
}

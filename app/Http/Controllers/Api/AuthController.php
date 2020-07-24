<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
    {

  $login=['name'=>$request->name,'password'=>$request->password];
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }



        if (Auth::attempt($login,true)) {

           $user=User::find(Auth::id());
           $user->api_token= Str::random(60);

           $user->save();


                $response = ['token' => $user->api_token, 'status' => 'success'];
                return response($response);

        } else {
            $response = ["message" => 'فشل فى المصادقة'];
            return response($response, 422);
        }

    }



    public function register(Request $request){




    }
}

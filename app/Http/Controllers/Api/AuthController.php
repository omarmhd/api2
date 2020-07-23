<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('name', $request->name)->first();

        if ($user) {

            if ($user->password == $request->password) {

                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token, 'status' => 'success'];
                return response($response);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }

    }
}

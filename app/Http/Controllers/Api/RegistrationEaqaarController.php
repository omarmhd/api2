<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationEaqaarController extends Controller
{
    public function store(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types',

        ], ['name.required' => 'الرجاء إدخال إسم الشركة']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Type = Type::create([

            'name' => $request->name
        ]);
        return response([
            'status' => 'success',
            'data' => $Type
        ]);
    }

}

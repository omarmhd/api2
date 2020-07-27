<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{


    public function show($id)
    {
        return response([
            'status' => 'success',
            'data' => Plan::where('type_id',$id)->get()
        ]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types'], ['name.required' => 'الرجاء إدخال إسم المخطط']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Type = Plan::create([

            'name' => $request->name,
            'type_id'=> $request->type_id,
        ]);
        return response([
            'status' => 'success',
            'data' => $Type
        ]);
    }



}

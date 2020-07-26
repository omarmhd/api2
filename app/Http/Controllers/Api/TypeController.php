<?php

namespace App\Http\Controllers\Api;

use App\DataCompany;
use App\Http\Controllers\Controller;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{

    public function index()
    {


        $types = Type::all();

        return response([
            'status' => 'success',
            'data' => $types


        ]);
    }

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


    public function update(Request $request, $id)
    {



        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types',

        ], ['name.required' => 'الرجاء إدخال  n']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Type = Type::find($id)->update([

            'name' => $request->name
        ]);
        return response([
            'status' => 'success',
            'data' => $Type
        ]);
    }


    public function destroy(Request $request, $id){

        $type=Type::find($id)->destroy;

        if($type){

            return response([
                'status' => 'success',

            ]);

        }
    }


}

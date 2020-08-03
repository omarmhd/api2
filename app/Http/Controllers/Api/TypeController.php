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

        ], ['name.required' => '']);

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

        $Type = Type::find($id)->update([

            'name' => $request->name
        ]);
        return response([
            'status' => 'نجاح ',
            'data' => $Type
        ]);
    }


    public function destroy(Request $request, $id){

        $type=Type::find($id)->delete();

        if($type){

            return response([
                'status'=>'نجاح ',
                'message'=>'تم الحذف بنجاح ',


            ]);

        }
    }


}

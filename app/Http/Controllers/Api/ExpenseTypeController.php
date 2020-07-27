<?php

namespace App\Http\Controllers\Api;

use App\Expense;
use App\Expense_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $types = Expense_type::all();

        return response([
            'status' => 'success',
            'data' => $types


        ]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:expense_types',

        ], ['name.required' => 'الرجاء إدخال  نوع المصروف','name.unique'=>'نوع المصروف موجود مسبقا']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Type = Expense_type::create([

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
            'name' => 'required|unique:expense_types',

        ], ['name.required' => 'الرجاء إدخال الإسم ',
        'name.unique' => 'الاسم موجود مسبقا ',]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Type = Expense_type::find($id)->update([

            'name' => $request->name
        ]);
        return response([
            'status' => 'success',
            'data' => $Type
        ]);
    }


    public function destroy(Request $request,$id){

        $type=Expense_type::find($id)->destroy;

        if($type){

            return response([
                'status' => 'تم الحذف بنجاح',

            ]);

        }
    }
}

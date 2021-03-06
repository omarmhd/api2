<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function tofrom(Request $request){
        $from =$request->from;
        $to = $request->to;
        $return_paginate = $request->return_paginate;

        $validator = Validator::make($request->all(), [
            'from' => 'required|date',
            'to'=>'required|date',

        ], [
           'from.required'=>'الرجاء إدخال التاريخ',
           'from.date'=>'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
           'to.required'=>'الرجاء إدخال التاريخ',
           'to.date'=>'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
           ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
            }

           if($return_paginate==1){
        $expenses=Expense::whereBetween('date', [$from, $to])->paginate(10);
        return ExpenseResource::collection($expenses);
    }else{
        $expenses=Expense::whereBetween('date', [$from, $to])->get();
        return ExpenseResource::collection($expenses);

    }}
    public function index()
    {


            $expenses=Expense::paginate(10);

        return ExpenseResource::collection($expenses);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'details' => 'required',
            'amount'=>'required|Numeric',
            'date'=>'required|date',

        ], ['details.required' => 'الرجاء إدخال تفاصيل المصروف',
           'amount.required'=>'الرجاء إدخال المبلغ ',
           'amount.Numeric'=>'  الرجاء إدخال المبلغ بشكل صحيخ ',
           'date.required'=>'الرجاء إدخال التاريخ',
           'date.date'=>'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
           ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $Expense = Expense::create([

            'type_id' => $request->type_id,
            'amount'=>$request->amount,
            'details'=>$request->details,
            'date' => $request->date,

        ]);
        return response([
            'status' => 'success',
            'data' => $Expense
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'details' => 'required',
            'amount'=>'required|Numeric',
            'date'=>'required|date',

        ], ['details.required' => 'الرجاء إدخال تفاصيل المصروف',
           'amount.required'=>'الرجاء إدخال المبلغ ',
           'amount.Numeric'=>'  الرجاء إدخال المبلغ بشكل صحيخ ',
           'date.required'=>'الرجاء إدخال التاريخ',
           'date.date'=>'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
           ]);

        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }

        $Expense = Expense::find($id)->update([

            'type_id' => $request->type_id,
            'amount'=>$request->amount,
            'details'=>$request->details,
            'date' => $request->date,

        ]);
        return response([
            'status' => 'تم تحديث المصروفات بنجاح ',
            'data' => $Expense
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exponse=Expense::where('id',$id)->delete();


        if($exponse){
        return response([
            'status' => 'تم الحذف بنجاح ',

        ]);
    }}
}

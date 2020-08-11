<?php

namespace App\Http\Controllers\Api;

use App\Eaqaar;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;

use App\Expense;
use App\Http\Resources\SoldEaqaarResource;
use App\Plan;
use App\Receivable;
use App\SoldEaqaar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SoldEaqaarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return SoldEaqaarResource::collection(SoldEaqaar::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            'name_buyer' => 'required',
            'card_buyer' => 'required|Numeric',
            'phone_buyer' => 'required|Numeric',
            'price_buy' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',
            'Downpayment' => 'required|Numeric',

        ], [
            'name_buyer.required' => 'الرجاء إدخال إسم المشترى ',
            'card_buyer.required' => 'الرجاء إدخال رقم بطاقة المشترى  ',
            'card_buyer.Numeric' => '    خطأ فى البطاقة المدخلة   ',
            'phone_buyer.required' => 'الرجاء إدخال رقم هاتف المشترى  ',
            'phone_buyer.Numeric' => '  خطأ فى إدخال رقم الهاتف  ',
            'price_buy.required' => 'الرجاء إدخال  سعر الشراء   ',
            'price_buy.Numeric' => '  خطأ فى إدخال سعر الشراء    ',
            'Date_sale.required' => 'الرجاءإدخال تاريخ البيع ',
            'Date_sale.date' => 'خطأ فى إدخال سعر البيع',
            'Downpayment.required' => 'الرجاء إدخال الدفعة الأولى',
            'Downpayment.Numeric' => '  خطأ فى إدخال الدفعة  الاولى ',


        ],);
        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }


        $Remaining_amount=$request->price_buy - $request->Downpayment;
        $sold_esqaar = SoldEaqaar::create([
            'user_id' => 1,
            "eaqaar_id" => $request->eaqaar_id,
            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_buy' => $request->price_buy,
            'Date_sale' => $request->Date_sale,
            'Downpayment' => $request->Downpayment,
            'Remaining_amount' =>$Remaining_amount,

            'due_date' => $request->due_date,
        ]);


        Eaqaar::find($request->eaqaar_id)->update([
            'state' => 'مباع',
        ]);

        Receivable::create([
        'eaqaar_id'=>$request->eaqaar_id,
        'type'=>'to',
        'Remaining_amount'=> $Remaining_amount,

        'date'=>$request->due_date
        ]);
        $plan=Eaqaar::find($request->eaqaar_id)->plan;
        Plan::where('id',$plan->id)->decrement("count",1);

        return SoldEaqaarResource::collection( $sold_esqaar);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SoldEaqaar  $soldEaqaar
     * @return \Illuminate\Http\Response
     */
    public function show(SoldEaqaar $soldEaqaar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SoldEaqaar  $soldEaqaar
     * @return \Illuminate\Http\Response
     */
    public function edit(SoldEaqaar $soldEaqaar)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SoldEaqaar  $soldEaqaar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'name_buyer' => 'required',
            'card_buyer' => 'required|Numeric',
            'phone_buyer' => 'equired|Numeric',
            'price_buy' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',

        ], [
            'name_buyer.required' => 'الرجاء إدخال إسم المشترى ',
            'card_buyer.required' => 'الرجاء إدخال رقم بطاقة المشترى  ',
            'card_buyer.Numeric' => '    خطأ فى البطاقة المدخلة   ',
            'phone_buyer.required' => 'الرجاء إدخال رقم هاتف المشترى  ',
            'phone_buyer.Numeric' => '  خطأ فى إدخال رقم الهاتف  ',
            'price_buy.required' => 'الرجاء إدخال  سعر الشراء   ',
            'price_buy.Numeric' => '  خطأ فى إدخال سعر الشراء    ',
            'Date_sale.required' => 'الرجاءإدخال تاريخ البيع ',
            'Date_sale.date' => 'خطأ فى إدخال سعر البيع',
            'due_date.required' => 'الرجاء إدخال المبلغ المتبقى   ',


        ],);
        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }




        $sold_esqaar = SoldEaqaar::find($id)->update([

            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_buy' => $request->price_buy,
            'Date_sale' => $request->Date_sale,
            'Remaining_amount' => $request->price_buy - $request->Downpayment,
            'Downpayment' => $request->Downpayment,
            'due_date' => $request->due_date,
        ]);

        $plan=Eaqaar::find($sold_esqaar->eaqaar_id)->plan;
        Plan::where('id',$plan->id)->update([

             'Remaining_amount'=>$request->price_buy - $request->Downpayment,
            'due_date'=>$request->due_date
        ]);
        return response([
            'status' => 'success',
            'data' => $sold_esqaar

        ]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SoldEaqaar  $soldEaqaar
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soldEaqaar = soldEaqaar::find($id)->delete();
        if ($soldEaqaar) {
            return response([
                'status' => 'تم الحذف بنجاح ',

            ]);
        }
    }
}

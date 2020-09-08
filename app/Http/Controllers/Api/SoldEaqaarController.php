<?php

namespace App\Http\Controllers\Api;

use App\Eaqaar;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;

use App\Expense;
use App\Http\Resources\SoldEaqaarResource;
use App\Http\Resources\SoldEaqaarSearchResource;
use App\Plan;
use App\Receivable;
use App\SoldEaqaar;
use App\User;
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
            'price_sell' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',
            'Downpayment' => 'required|Numeric',

        ], [
            'name_buyer.required' => 'الرجاء إدخال إسم المشترى ',
            'card_buyer.required' => 'الرجاء إدخال رقم بطاقة المشترى  ',
            'card_buyer.Numeric' => '    خطأ فى البطاقة المدخلة   ',
            'price_sell.required' => 'الرجاء إدخال رقم هاتف المشترى  ',
            'price_sell.Numeric' => '  خطأ فى إدخال رقم الهاتف  ',
            'price_sell.required' => 'الرجاء إدخال  سعر الشراء   ',
            'price_sell.Numeric' => '  خطأ فى إدخال سعر الشراء    ',
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



        $Remaining_amount = $request->price_sell - $request->Downpayment;


        $user = User::find(auth('api')->user()->id);

        $number_deals = $user->number_deals + 1;
        $profit_broker1 = $user->profit_broker;
        $profit_company1 = $user->Profit_Company;


        $eqaar = Eaqaar::find($request->eaqaar_id);
        $profit_broker = ($request->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
        $profit_company = ($request->price_sell - $eqaar->price_buy) * (100 - $user->Commission) / 100;
        $profit_broker1 = $profit_broker1 + $profit_broker;
        $profit_company1 = $profit_company1 + $profit_company;


        $user->update([
            'number_deals' => $number_deals,
            'profit_broker' => $profit_broker1,
            'Profit_Company' => $profit_company1
        ]);

        $sold_esqaar = SoldEaqaar::create([
            'user_id' => auth('api')->user()->id,
            'eaqaar_id' => $request->eaqaar_id,
            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_sell' => $request->price_sell,
            'Date_sale' => $request->Date_sale,
            'Downpayment' => $request->Downpayment,
            'Remaining_amount' => $Remaining_amount,
            'due_date' => $request->due_date,

            'profit_company' => $profit_company,
            'Partial_condition' => $request->Partial_condition,
            'image_card' => $this->upload_image($request->image_card)
        ]);






        Eaqaar::where('id', $request->eaqaar_id)->update([
            'status' => 'مباع',
        ]);
        $receivable = Receivable::where('sold_id', $sold_esqaar->id)->first();


        if ($Remaining_amount !== 0) {
            Receivable::create([
                'eaqaar_id' => $request->eaqaar_id,

                'sold_id' =>  $sold_esqaar->id,
                'type' => 'to',
                'user_name' => $request->name_buyer,
                'Remaining_amount' => $Remaining_amount,
                'date' => $request->due_date
            ]);
        }
        if ($Remaining_amount == 0 and $receivable) {

            $receivable->delete();
        }


        $sold = SoldEaqaar::orderBy('id', 'desc')->take(1)->get();
        $plan = Eaqaar::find($request->eaqaar_id)->plan;
        $Plan = Plan::find($plan->id);
        $count = $Plan->count - 1;
        $Plan->update(['count' =>  $count]);

        return SoldEaqaarResource::collection($sold);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'name_buyer' => 'required',
            'card_buyer' => 'required|Numeric',
            'phone_buyer' => 'required|Numeric',
            'price_sell' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',

        ], [
            'name_buyer.required' => 'الرجاء إدخال إسم المشترى ',
            'card_buyer.required' => 'الرجاء إدخال رقم بطاقة المشترى  ',
            'card_buyer.Numeric' => '    خطأ فى البطاقة المدخلة   ',
            'phone_buyer.required' => 'الرجاء إدخال رقم هاتف المشترى  ',
            'phone_buyer.Numeric' => '  خطأ فى إدخال رقم الهاتف  ',
            'price_sell.required' => 'الرجاء إدخال  سعر الشراء   ',
            'price_sell.Numeric' => '  خطأ فى إدخال سعر الشراء    ',
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

        if ($file = $request->file('image')) {

            $image_name = $this->upload_image($file);
        } else {
            $image_name = null;
        }
        $sold_esqaar = SoldEaqaar::find($id);


        $user = User::find($sold_esqaar->user->id);
        $profit_broker1 = $user->profit_broker;
        $profit_company1 = $user->Profit_Company;

        //
        $eqaar = Eaqaar::find($sold_esqaar->eaqaar_id);

        if ($sold_esqaar->price_sell != $request->price_sell) {

            $profit_broker = ($sold_esqaar->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
            $profit_company = ($sold_esqaar->price_sell - $eqaar->price_buy) * (100 - $user->Commission) / 100;

            $profit_broker1 = abs($profit_broker1 - $profit_broker);
            $profit_company1 = abs($profit_company1 - $profit_company);

            $profit_broker2 = ($request->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
            $profit_company2 = ($request->price_sell - $eqaar->price_buy) * (100 - $user->Commission) / 100;

            $profit_broker3 = $profit_broker1 + $profit_broker2;
            $profit_company3 = $profit_company2 + $profit_company1;


            $user->update([
                'profit_broker' => $profit_broker3,
                'Profit_Company' => $profit_company3
            ]);
        }
        //
        $sold_esqaar->update([

            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_sell' => $request->price_sell,
            'Date_sale' => $request->Date_sale,
            'Remaining_amount' => $request->price_sell - $request->Downpayment,
            'Downpayment' => $request->Downpayment,
            'due_date' => $request->due_date,
            'image_card' => $image_name,
            'profit_company' => $profit_company2,
            'Partial_condition' => $request->Partial_condition

        ]);

        $plan = Eaqaar::find($sold_esqaar->eaqaar_id)->plan;




        $receivable = Receivable::where('sold_id', $sold_esqaar->id)->first();

        if ($request->price_sell - $request->Downpayment == 0 and $receivable) {

            $receivable->delete();
        } else {

            $receivable = Receivable::where('sold_id', '=', $id)->update([
                'Remaining_amount' => $request->price_sell - $request->Downpayment,
                'date' => $request->due_date,
                'name_buyer' => $request->name_buyer
            ]);
        }
        if ($file = $request->file('image')) {

            $sold_esqaar->image = asset('upload_images/' . $this->upload_image($file));
        }


        return response([
            'status' => 'success',
            'data' => $sold_esqaar

        ]);
    }

    public function search_eqaars(Request $request)
    {




        $search = $request->search;
        $Eaqaar = Eaqaar::where('status', 'مباع')->where('plan_id', $request->plan_id)->where(function ($query) use ($search) {
            $query->where('state', 'like', '%' . $search . '%')
                ->orwhere('area', 'like', '%' . $search . '%')
                ->orwhere('square', 'like', '%' . $search . '%')
                ->orwhere('space', 'like', '%' . $search . '%')
                ->orwhere('estimated_price', 'like', '%' . $search . '%')
                ->orwhere('detials', 'like', '%' . $search . '%');
        })->paginate(10);
        return SoldEaqaarSearchResource::collection($Eaqaar);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SoldEaqaar  $soldEaqaar
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soldEaqaar = soldEaqaar::find($id);




        $user = User::find($soldEaqaar->user->id);

        $eqaar = Eaqaar::find($soldEaqaar->eaqaar_id);

        $profit_broker = ($soldEaqaar->price_sell - $eqaar->price_buy) * ($user->Commission / 100);

        $profit_broker1 = $user->profit_broker;
        $profit_company1 = $user->Profit_Company;

        $user->update([
            'profit_broker' =>  abs($profit_broker1 - $profit_broker),
            'Profit_Company' => abs($profit_company1 - $soldEaqaar->profit_company),
            'number_deals' => $user->number_deals - 1

        ]);

        $Receivable = Receivable::where('sold_id', '=', $id)->delete();

        //

        $plan = Eaqaar::find($soldEaqaar->eaqaar_id)->plan;
        $Plan = Plan::find($plan->id);
        $count = $Plan->count + 1;
        $Plan->update(['count' =>  $count]);


        $eqaar->update([
            'status' => 'متوفر',
        ]);


        $soldEaqaar->delete();
        if ($soldEaqaar) {
            return response([
                'status' => 'تم الحذف بنجاح ',

            ]);
        }
    }

    public function upload_image($file)
    {
        if ($file) {
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move('upload_images', $imageName);

            return $imageName;
        } else {
            return null;
        }
    }


    public function paginate_eqaars()
    {
        return SoldEaqaarResource::collection(SoldEaqaar::paginate(10));
    }
}

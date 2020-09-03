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
            'Partial_condition'=>$request->Partial_condition,
            'image_card' => $this->upload_image($request->image_card)
        ]);






        Eaqaar::where('id',$request->eaqaar_id)->update([
            'status' => 'مباع',
        ]);

        $receivable = Receivable::where('eaqaar_id', $request->eaqaar_id)->first();

        if ($Remaining_amount !== 0 ) {
            Receivable::create([
                'eaqaar_id' => $request->eaqaar_id,

                'sold_id' =>  $sold_esqaar->sold_id,
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
            'phone_buyer' => 'equired|Numeric',
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
        $sold_esqaar = SoldEaqaar::find($id)->update([

            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_sell' => $request->price_sell,
            'Date_sale' => $request->Date_sale,
            'Remaining_amount' => $request->price_sell - $request->Downpayment,
            'Downpayment' => $request->Downpayment,
            'due_date' => $request->due_date,
            'image_card' => $image_name,
            'profit_company' => $profit_company,
            'Partial_condition'=>$request->Partial_condition

        ]);

        $plan = Eaqaar::find($sold_esqaar->eaqaar_id)->plan;

        $receivable = Receivable::where('sold_id', $id)->update([
             'user_name' => $request->name_buyer,
            'Remaining_amount' => $request->price_sell - $request->Downpayment,
            'date' => $request->due_date
    ]);
        if ($file = $request->file('image')) {

            $sold_esqaar->image = asset('upload_images/' . $this->upload_image($file));
        }


        return response([
            'status' => 'success',
            'data' => $sold_esqaar

        ]);
    }

    public function search_eqaars(Request $request){




        $search=$request->search;
        $Eaqaar = Eaqaar::where('plan_id', $request->plan_id)->where('status','مباع')->where(function($query) use ($search) {
            $query->where('state','like','%'. $search .'%')
            ->orwhere('area','like','%'.$search .'%')
            ->orwhere('square','like','%'. $search .'%')
            ->orwhere('space','like','%'. $search .'%')
            ->orwhere('estimated_price','like','%'. $search .'%')
            ->orwhere('detials','like','%'. $search .'%');
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

        $Receivable = Receivable::where('eaqaar_id', $soldEaqaar->eaqaar_id)->delete();
        $soldEaqaar->delete();

        $plan = Eaqaar::find($soldEaqaar->eaqaar_id)->plan;
        $Plan = Plan::find($plan->id);
        $count = $Plan->count + 1;
        $Plan->update(['count' =>  $count]);

        Eaqaar::where('id',$soldEaqaar->eaqaar_id)->update([
            'status' => 'متوفر',
        ]);
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


    public function paginate_eqaars(){
        return SoldEaqaarResource::collection(SoldEaqaar::paginate(10));

    }

}

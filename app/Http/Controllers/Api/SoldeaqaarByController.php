<?php

namespace App\Http\Controllers\Api;

use App\Eaqaar;
use App\Http\Controllers\Controller;
use App\Http\Resources\SoldEaqaarResource;
use App\Http\Resources\SoldEaqaarSearchResource;
use App\Plan;
use App\Receivable;
use App\SoldEaqaar;
use App\soldeaqaar_by;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoldeaqaarByController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            'state' => 'required',
            'area' => 'required',
            'square' => 'required',
            'Part_number' => 'required',
            'space' => 'required',
            'Survey_number' => 'required',
            'name_seller' => 'required|string',
            'card_seller' => 'required|Numeric',
            'phone_seller' => 'required|Numeric',
            'price_buy' => 'required|Numeric',
            'image' => 'nullable|image',
            'url'=>'nullable|url',
            'name_buyer' => 'required',
            'card_buyer' => 'required|Numeric',
            'price_sell' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',
            'Downpayment' => 'required|Numeric',
            'use'=>'nullable'

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $eqaar = new Eaqaar();
        $eqaar->user_id =1;//auth('api')->user()->id;
         $eqaar->plan_id = $request->plan_id;
         $eqaar->state = $request->state;
         $eqaar->area = $request->area;
         $eqaar->square = $request->square;
         $eqaar->Part_number = $request->Part_number;
         $eqaar->space = $request->space;
         $eqaar->Survey_number = $request->Survey_number;
         $eqaar->name_seller = $request->name_seller;
         $eqaar->card_seller = $request->card_seller;
         $eqaar->phone_seller = $request->phone_seller;
         $eqaar->price_buy = $request->price_buy;
         $eqaar->estimated_price = $request->estimated_price;
         $eqaar->detials = $request->detials;
         $eqaar->url = $request->url;
         $eqaar->use = $request->use;
         $eqaar->status = 'مباع';
         $eqaar->Downpayment=0;
         $eqaar->estimated_price=0;
         if ( $request->file('image')) {
            $file = $request->file('image');
            $eqaar->image = asset('upload_images/'.$this->upload_image($file));
        }
        $eqaar->notes= $request->notes;

        $eqaar->save();



         $user = User::find(1);
         $number_deals = $user->number_deals + 1;
         $profit_broker1 = $user->profit_broker;
         $profit_company1 = $user->Profit_Company;
         $profit_purchase = $user->profit_purchase;

         $profit_broker1 += ($request->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
        // $profit_company = ($request->price_sell - $eqaar->price_buy) * (100 - $user->Commission) / 100;


        $profit_company= ($request->price_sell - $eqaar->price_buy) * (100 - abs($user->Commission-$user->purchase_commission )) / 100;
        $profit_company1+= $profit_company;
        $profit_purchase+= ($request->price_sell - $eqaar->price_buy) * ($user->purchase_commission / 100);
        //$profit_company1 = $profit_company1 + $profit_company;





         $user->update([
             'number_deals' => $number_deals,
             'profit_broker' => $profit_broker1,
             'Profit_Company' => $profit_company1,
             'profit_purchase'=>$profit_purchase
         ]);

         $Remaining_amount= $request->price_sell -$request->Downpayment;
        $sold_esqaar = SoldEaqaar::create([
            'user_id' => 1,//auth('api')->user()->id,
            'eaqaar_id' => $eqaar->id ,
            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_sell' => $request->price_sell,
            'Date_sale' => $request->Date_sale,
            'Downpayment' => $request->Downpayment,
            'Remaining_amount' =>  $request->price_sell -$request->Downpayment,
            'due_date' => $request->due_date,
            'type'=>"between",
            'profit_company' => $profit_company,
            'Partial_condition'=>$request->Partial_condition,
            'image_card' => $this->upload_image($request->image_card)
        ]);
        $sold = SoldEaqaar::orderBy('id', 'desc')->take(1)->get();


        if ($Remaining_amount !== 0 ) {
            Receivable::create([
                'eaqaar_id' => $eqaar->id ,

                'sold_id' => $sold_esqaar->id,
                'type' => 'to',
                'user_name' => $request->name_buyer,
                'Remaining_amount' => $Remaining_amount,
                'date' => $request->due_date
            ]);
        }

        return SoldEaqaarResource::collection($sold);

    }


    public function show($id)
    {
        $sold=SoldEaqaar::where('user_id',$id)->where('type','between')->get();

        return SoldEaqaarResource::collection($sold);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\soldeaqaar_by  $soldeaqaar_by
     * @return \Illuminate\Http\Response
     */
    public function edit(soldeaqaar_by $soldeaqaar_by)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\soldeaqaar_by  $soldeaqaar_by
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'state' => 'required',
            'area' => 'required',
            'square' => 'required',
            'Part_number' => 'required',
            'space' => 'required',
            'Survey_number' => 'required',
            'name_seller' => 'required|string',
            'card_seller' => 'required|Numeric',
            'phone_seller' => 'required|Numeric',
            'price_buy' => 'required|Numeric',
            'image' => 'nullable|image',
            'url'=>'nullable|url',
            'name_buyer' => 'required',
            'card_buyer' => 'required|Numeric',
            'price_sell' => 'required|Numeric',
            'Date_sale' => 'required|date',
            'due_date' => 'required|date',
            'Downpayment' => 'required|Numeric',

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }



        $Remaining_amount= $request->price_sell -$request->Downpayment;
       $sold_esqaar = SoldEaqaar::where('id',$id);
       $eqaar = Eaqaar::find($sold_esqaar->eaqaar_id);



       $user = User::find($sold_esqaar->user->id);
       $profit_broker1 = $user->profit_broker;
       $profit_company1 = $user->Profit_Company;
       $number_deals = $user->number_deals + 1;
       $profit_purchase1= $user->profit_purchase;
       //
       $eqaar = Eaqaar::find($sold_esqaar->eaqaar_id);
       $profit_company2 = ($request->price_sell - $request->price_buy) * (100 -abs($user->Commission-$user->purchase_commission )) / 100;

       if ($sold_esqaar->price_sell != $request->price_sell) {



           $profit_broker = ($sold_esqaar->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
           $profit_company= ($sold_esqaar->price_sell - $eqaar->price_buy) * (100 - abs($user->Commission-$user->purchase_commission )) / 100;
           $profit_purchase = ($sold_esqaar->price_sell - $eqaar->price_buy) * ($user->purchase_commission / 100);

           $profit_broker2 = ($request->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
           $profit_purchase2 = ($request->price_sell - $eqaar->price_buy) * ($user->purchase_commission / 100);

           $profit_broker1 = abs($profit_broker1 - $profit_broker);
           $profit_company1 = abs($profit_company1 - $profit_company);
           $profit_purchase1 = abs($profit_purchase1 - $profit_purchase);

           $profit_broker3 = $profit_broker1 + $profit_broker2;
           $profit_company3 = $profit_company2 + $profit_company1;
           $profit_purchase3 = $profit_purchase2 + $profit_purchase1;


           $user->update([
               'profit_broker' => $profit_broker3,
               'Profit_Company' => $profit_company3,
               'profit_purchase' => $profit_purchase3

           ]);
       }
       $sold_esqaar->update([
           'name_buyer' => $request->name_buyer,
           'card_buyer' => $request->card_buyer,
           'phone_buyer' => $request->phone_buyer,
           'price_sell' => $request->price_sell,
           'Date_sale' => $request->Date_sale,
           'Downpayment' => $request->Downpayment,
           'Remaining_amount' =>  $request->price_sell -$request->Downpayment,
           'due_date' => $request->due_date,
           'type'=>"by",
           'profit_company' => $profit_company2,
           'Partial_condition'=>$request->Partial_condition,
           'image_card' => $this->upload_image($request->image_card)
       ]);


       $sold = SoldEaqaar::find($id);


        $eqaar->state = $request->state;
        $eqaar->area = $request->area;
        $eqaar->square = $request->square;
        $eqaar->Part_number = $request->Part_number;
        $eqaar->space = $request->space;
        $eqaar->Survey_number = $request->Survey_number;
        $eqaar->name_seller = $request->name_seller;
        $eqaar->card_seller = $request->card_seller;
        $eqaar->phone_seller = $request->phone_seller;
        $eqaar->price_buy = $request->price_buy;
        $eqaar->estimated_price = $request->estimated_price;
        $eqaar->detials = $request->detials;
        $eqaar->url = $request->url;
        $eqaar->use = $request->use;
        $eqaar->status = 'مباع';
        $eqaar->Downpayment=0;
        $eqaar->estimated_price=0;
        if ( $request->file('image')) {
           $file = $request->file('image');
           $eqaar->image = asset('upload_images/'.$this->upload_image($file));
       }
       $eqaar->save();



       $receivable = Receivable::where('sold_id', $sold_esqaar->id)->first();

       if ($request->price_sell - $request->Downpayment == 0 and $receivable) {

           $receivable->delete();
       } else {

           $receivable = Receivable::where('sold_id', '=', $id)->update([
               'Remaining_amount' => $request->price_sell - $request->Downpayment,
               'date' => $request->due_date,
               'user_name' => $request->name_buyer
           ]);
       }
       if($request->price_sell - $request->Downpayment!==0 and  $receivable==null ){
           Receivable::create([
               'eaqaar_id' => $eqaar->id,

               'sold_id' =>  $sold_esqaar->id,
               'type' => 'to',
               'user_name' => $request->name_buyer,
               'Remaining_amount' => $request->price_sell - $request->Downpayment,
               'date' => $request->due_date
           ]);

       }
   return SoldEaqaarResource::collection($sold_esqaar->get());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\soldeaqaar_by  $soldeaqaar_by
     * @return \Illuminate\Http\Response
     */
    public function destroy(  $id)
    {

        return response([
            'status' => 'تم الحذف بنجاح ',

        ]);
        $soldEaqaar = soldEaqaar::find($id);


        dd('2');


       $user=User::find($soldEaqaar->user->id);

       $eqaar = Eaqaar::find($soldEaqaar->eaqaar_id);
       $profit_broker = ($soldEaqaar->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
       $profit_purchase = ($soldEaqaar->price_sell - $eqaar->price_buy) * ($user->purchase_commission / 100);

       $profit_broker1 = $user->profit_broker;
       $profit_company1 = $user->Profit_Company;
       $profit_purchase1=$user->profit_purchase;

       dd('2');

    $user->update([
           'profit_broker' =>  abs($profit_broker1-$profit_broker),
           'Profit_Company' => abs($profit_company1-$soldEaqaar->profit_company),
           'profit_purchase'=>abs($profit_purchase1-$profit_purchase),
           'number_deals'=> $user->number_deals-1

           ]);

        $Receivable = Receivable::where('sold_id','=',$id)->delete();

        //



        $eqaar->delete();
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
}

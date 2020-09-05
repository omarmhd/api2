<?php

namespace App\Http\Controllers\Api;

use App\Eaqaar;
use App\Http\Controllers\Controller;
use App\Http\Resources\SoldEaqaarResource;
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

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }


        $eqaar = new Eaqaar();
        $eqaar->user_id =auth('api')->user()->id;
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
        $eqaar->save();



         $user = User::find(auth('api')->user()->id);
         $number_deals = $user->number_deals + 1;
         $profit_broker1 = $user->profit_broker;
         $profit_company1 = $user->Profit_Company;


         $profit_broker = ($request->price_sell - $eqaar->price_buy) * ($user->Commission / 100);
         $profit_company = ($request->price_sell - $eqaar->price_buy) * (100 - $user->Commission) / 100;
         $profit_broker1 = $profit_broker1 + $profit_broker;
         $profit_company1 = $profit_company1 + $profit_company;


         $user->update([
             'number_deals' => $number_deals,
             'profit_broker' => $profit_broker1,
             'Profit_Company' => $profit_company1
         ]);

         $Remaining_amount= $request->price_sell -$request->Downpayment;
        $sold_esqaar = SoldEaqaar::create([
            'user_id' => auth('api')->user()->id,
            'eaqaar_id' => $eqaar->id ,
            'name_buyer' => $request->name_buyer,
            'card_buyer' => $request->card_buyer,
            'phone_buyer' => $request->phone_buyer,
            'price_sell' => $request->price_sell,
            'Date_sale' => $request->Date_sale,
            'Downpayment' => $request->Downpayment,
            'Remaining_amount' =>  $request->price_sell -$request->Downpayment,
            'due_date' => $request->due_date,
            'type'=>"by",
            'profit_company' => $profit_company,
            'Partial_condition'=>$request->Partial_condition,
            'image_card' => $this->upload_image($request->image_card)
        ]);
        $sold = SoldEaqaar::orderBy('id', 'desc')->take(1)->get();



        if ($Remaining_amount !== 0 ) {
            Receivable::create([
                'eaqaar_id' => $eqaar->id ,

                'sold_id' => $sold->id,
                'type' => 'to',
                'user_name' => $request->name_buyer,
                'Remaining_amount' => $Remaining_amount,
                'date' => $request->due_date
            ]);
        }
        return SoldEaqaarResource::collection($sold);

    }


    public function show(soldeaqaar_by $soldeaqaar_by)
    {

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


        $user = User::find(auth('api')->user()->id);
        $number_deals = $user->number_deals + 1;
        $profit_broker1 = $user->profit_broker;
        $profit_company1 = $user->Profit_Company;


        $profit_broker = ($request->price_sell - $request->price_buy) * ($user->Commission / 100);
        $profit_company = ($request->price_sell - $request->price_buy) * (100 - $user->Commission) / 100;
        $profit_broker1 = $profit_broker1 + $profit_broker;
        $profit_company1 = $profit_company1 + $profit_company;


        $user->update([
            'number_deals' => $number_deals,
            'profit_broker' => $profit_broker1,
            'Profit_Company' => $profit_company1
        ]);

        $Remaining_amount= $request->price_sell -$request->Downpayment;
       $sold_esqaar = SoldEaqaar::where('id',$id)->update([
           'name_buyer' => $request->name_buyer,
           'card_buyer' => $request->card_buyer,
           'phone_buyer' => $request->phone_buyer,
           'price_sell' => $request->price_sell,
           'Date_sale' => $request->Date_sale,
           'Downpayment' => $request->Downpayment,
           'Remaining_amount' =>  $request->price_sell -$request->Downpayment,
           'due_date' => $request->due_date,
           'type'=>"by",
           'profit_company' => $profit_company,
           'Partial_condition'=>$request->Partial_condition,
           'image_card' => $this->upload_image($request->image_card)
       ]);


       $sold = SoldEaqaar::find($id);

       $eqaar = Eaqaar::find( $sold->eaqaar_id);
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


       $receivable = Receivable::where('eaqaar_id', $sold->eaqaar_id)->first();

       if ($Remaining_amount !== 0 ) {
           Receivable::create([
               'eaqaar_id' => $eqaar->id ,

               'sold_id' => $sold->id,
               'type' => 'to',
               'user_name' => $request->name_buyer,
               'Remaining_amount' => $Remaining_amount,
               'date' => $request->due_date
           ]);
       }
       if ($Remaining_amount == 0 and $receivable) {

        $receivable->delete();
    }
       return SoldEaqaarResource::collection($sold);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\soldeaqaar_by  $soldeaqaar_by
     * @return \Illuminate\Http\Response
     */
    public function destroy(soldeaqaar_by $soldeaqaar_by)
    {
        //
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

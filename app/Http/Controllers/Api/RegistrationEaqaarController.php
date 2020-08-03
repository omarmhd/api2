<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\RegistrationEaqaar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegistrationEaqaarController extends Controller
{

   public function index(){

    $RegistrationEaqaars=RegistrationEaqaar::all();
    return response([
        'status' => 'success',
        'data' =>$RegistrationEaqaars

    ]);
   }



    public function store(Request $request)
    {



        $validator = Validator::make($request->all(), [

            'state'=> 'required',
           'area'=> 'required|Numeric',
           'square'=> 'required|Numeric',
            'Part_number'=> 'required|Numeric',
            'space'=> 'required|Numeric',
            'Survey_number'=> 'required|Numeric',
           'name_seller'=> 'required|string',
            'card_seller'=> 'required|Numeric',
           'phone_seller'=> 'required|Numeric',
            'date_buy'=> 'required|date',
            'price_buy'=> 'required|Numeric',
           'Downpayment'=> 'required|Numeric',
            'estimated_price' => 'required|Numeric',

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $Users = RegistrationEaqaar::create([
            'user_id'=>Auth::id(),
            'type_id'=> $request->type_id,
            'plan_id'=> $request->plan_id,
            'state' => $request->state,
            'area' => $request->area,
            'square' =>  $request->square,
            'Part_number' => $request->Part_number,
            'space' => $request->space,
            'Survey_number' => $request->Survey_number,
            'name_seller' => $request->name_seller,
            'card_seller'=> $request->card_seller,
            'phone_seller' => $request->phone_seller,
            'date_buy' => $request->date_buy,
            'price_buy' => $request->price_buy,
            'Downpayment' => $request->Downpayment,
            'estimated_price' => $request->estimated_price,

        ]);
        return response([
            'status' => 'تمت بنجاح',
        ]);

    }

}

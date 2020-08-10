<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EaqaarResource;
use App\Eaqaar;
use App\Plan;
use App\Receivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationEaqaarController extends Controller
{

    public function index()
    {

        $Eaqaar = Eaqaar::all();
        return EaqaarResource::collection($Eaqaar);
    }


    public function store(Request $request)
    {



        $validator = Validator::make($request->all(), [

            'state' => 'required',
            'area' => 'required|Numeric',
            'square' => 'required|Numeric',
            'Part_number' => 'required|Numeric',
            'space' => 'required|Numeric',
            'Survey_number' => 'required|Numeric',
            'name_seller' => 'required|string',
            'card_seller' => 'required|Numeric',
            'phone_seller' => 'required|Numeric',
            'date_buy' => 'required|date',
            'price_buy' => 'required|Numeric',
            'Downpayment' => 'required|Numeric',
            'estimated_price' => 'required|Numeric',
            'Remaining_amount' => 'required|Numeric',

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $Remaining_amount = $request->price_buy - $request->Downpayment;

        $eqaar = new Eaqaar();
        $eqaar->user_id = '1'; //auth('api')->user()->id;
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
        $eqaar->date_buy = $request->date_buy;
        $eqaar->price_buy = $request->price_buy;
        $eqaar->Downpayment = $request->Downpayment;
        $eqaar->estimated_price = $request->estimated_price;
        $eqaar->Remaining_amount =  $Remaining_amount;
        $eqaar->detials = $request->detials;
        $eqaar->due_date = $request->due_date;

        if ($file = $request->file('image')) {

            $eqaar->image = $this->upload_image($file);
        }
        $eqaar->save();

        $eaqaar = Eaqaar::orderBy('id', 'desc')->take(1)->get();
        $plan = Eaqaar::find($request->eaqaar_id)->plan;

        Plan::where('id', $plan->id)->increment("count", 1);
        Receivable::create([
            'eaqaar_id' => $eqaar->id,
            'type' => 'on',
            'Remaining_amount' => $Remaining_amount,
            'date' => $request->due_date
        ]);
        return EaqaarResource::collection($eaqaar);
    }

    public function Update(Request $request, $id)
    {



        $validator = Validator::make($request->all(), [

            'state' => 'required',
            'area' => 'required|Numeric',
            'square' => 'required|Numeric',
            'Part_number' => 'required|Numeric',
            'space' => 'required|Numeric',
            'Survey_number' => 'required|Numeric',
            'name_seller' => 'required|string',
            'card_seller' => 'required|Numeric',
            'phone_seller' => 'required|Numeric',
            'date_buy' => 'required|date',
            'price_buy' => 'required|Numeric',
            'Downpayment' => 'required|Numeric',
            'estimated_price' => 'required|Numeric',
            'image' => 'nullable|image'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $eqaar = Eaqaar::find($id);;

        $eqaar->type_id = $request->type_id;
        $eqaar->plan_id = $request->plan_id;
        $eqaar->state = 'متوفر';
        $eqaar->area = $request->area;
        $eqaar->square = $request->square;
        $eqaar->Part_number = $request->Part_number;
        $eqaar->space = $request->space;
        $eqaar->Survey_number = $request->Survey_number;
        $eqaar->name_seller = $request->name_seller;
        $eqaar->card_seller = $request->card_seller;
        $eqaar->phone_seller = $request->phone_seller;
        $eqaar->date_buy = $request->date_buy;
        $eqaar->price_buy = $request->price_buy;
        $eqaar->Downpayment = $request->Downpayment;
        $eqaar->estimated_price = $request->estimated_price;
        $eqaar->Remaining_amount = $request->price_buy - $request->Downpayment;
        $eqaar->estimated_price = $request->estimated_price;
        $eqaar->detials = $request->detials;

        if ($file = $request->file('image')) {

            $eqaar->image = $this->upload_image($file);
        }
        $eqaar->save;

        return response([
            'status' => 'success',
            'data' => $eqaar,
        ]);
    }

    public function destroy($id)
    {

        $Eaqaar = Eaqaar::find($id)->delete();

        if ($Eaqaar) {
            return response([
                'status' => 'تم حذف العقار بنجاح ',
            ]);
        }
    }

    public function upload_image($file)
    {


        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move('upload_images', $imageName);

        return $imageName;
    }
}

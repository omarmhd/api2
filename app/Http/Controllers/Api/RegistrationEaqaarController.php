<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EaqaarResource;
use App\Eaqaar;
use App\Plan;
use App\Receivable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsEmpty;

class RegistrationEaqaarController extends Controller
{

    public function index()
    {

        $Eaqaar = Eaqaar::where('status','متوفر')->get();
        return EaqaarResource::collection($Eaqaar);
    }


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
            'estimated_price' => 'required|Numeric',
            'image_card' => 'nullable|image',
            'image' => 'nullable|image',
            'url'=>'nullable|url',
            'due_date' => 'required|date',
            'date_buy' => 'required|date',

            'Downpayment' => 'required|Numeric',

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $Remaining_amount = $request->price_buy - $request->Downpayment;

        $eqaar = new Eaqaar();
       $eqaar->user_id =6;
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
        $eqaar->url = $request->url;
        $eqaar->use = $request->use;
        $eqaar->use=$request->notes;

        $eqaar->status = 'متوفر';

        if ( $request->file('image')) {
            $file = $request->file('image');
            $eqaar->image = asset('upload_images/'.$this->upload_image($file));
        }

        if( $file2=$request->file('image_card')) {

        $image = time()+'2' . '.' . $file2->getClientOriginalExtension();
        $file2->move('upload_images', $image);

            $eqaar->image_card = asset('upload_images/'. $image);
        }
        $eqaar->save();

        $user=User::where('id',6);


       $eaqaar = Eaqaar::orderBy('id','desc')->take(1)->get();

       $Plan = Plan::find($eqaar->plan_id);
       $count = $Plan->count + 1;
       $Plan->update(['count' =>  $count]);


   // Plan::find($eqaar->plan_id)->increment('count', 1);
   if( $Remaining_amount>0){
        Receivable::create([
            'eaqaar_id' => $eqaar->id,
            'type' => 'on',
            'user_name' => $request->name_seller,
            'Remaining_amount' => $Remaining_amount,
            'date' => $request->due_date
        ]);}
        return EaqaarResource::collection( $eaqaar);
    }

    public function Update(Request $request, $id)
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
            'date_buy' => 'required|date',
            'price_buy' => 'required|Numeric',
            'Downpayment' => 'required|Numeric',
            'estimated_price' => 'required|Numeric',
            'image' => 'nullable|image',
            'image_card' => 'nullable|image',
            'url'=>'nullable|url'

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $Remaining_amount =  $request->price_buy - $request->Downpayment;
        $eqaar = Eaqaar::find($id);

        $eqaar->type_id = $request->type_id;
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
        $eqaar->Remaining_amount = $request->price_buy - $request->Downpayment;
        $eqaar->estimated_price = $request->estimated_price;
        $eqaar->detials = $request->detials;
        $eqaar->due_date = $request->due_date;
        $eqaar->url = $request->url;
        $eqaar->use = $request->use;


        if ($file = $request->file('image')) {

            $eqaar->image = asset('upload_images/'.$this->upload_image($file));
        }
        if ($file2 = $request->file('image_card')) {

            $image = time()+'2' . '.' . $file2->getClientOriginalExtension();
            $file2->move('upload_images', $image);

                $eqaar->image_card = asset('upload_images/'. $image);
        }

        $eqaar->save();

        $receivable=Receivable::where('eaqaar_id',$id)->first();
        if( $Remaining_amount==0 ){

            $receivable->delete();

        }
        if($Remaining_amount!==0 and  $receivable==null ){
            Receivable::create([
                'eaqaar_id' => $eqaar->id,
                'type' => 'on',
                'user_name' => $request->name_seller,
                'Remaining_amount' => $Remaining_amount,
                'date' => $request->due_date
            ]);

        }
        if( $Remaining_amount>0){

        Receivable::where('eaqaar_id',$id)->update([
            'user_name' => $request->name_seller,

            'Remaining_amount' => $Remaining_amount,
            'date' => $request->due_date
        ]);}
        return response([
            'status' => 'success',
            'data' => $eqaar,
        ]);
    }

    public function destroy($id)
    {
        $Eaqaar = Eaqaar::find($id);
        $Plan = Plan::find($Eaqaar->plan_id);
        $count = $Plan->count - 1;
        $Plan->update(['count' =>  $count]);
        $Eaqaar->delete();
        Receivable::where('eaqaar_id',$id)->delete();



        if ($Eaqaar) {
            return response([
                'status' => 'success',
            ]);
        }
    }



    public function search_eqaars(Request $request){


            $search=$request->search;
            $Eaqaar = Eaqaar::where('plan_id', $request->plan_id)->where('status','متوفر')->where(function($query) use ($search) {
                $query->where('state','like','%'. $search .'%')
                ->orwhere('area','like','%'.$search .'%')
                ->orwhere('square','like','%'. $search .'%')
                ->orwhere('space','like','%'. $search .'%')
                ->orwhere('estimated_price','like','%'. $search .'%')
                ->orwhere('detials','like','%'. $search .'%');
            })->paginate(10);
        return EaqaarResource::collection($Eaqaar);

    }
    public function upload_image($file)
    {


        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move('upload_images', $imageName);

        return $imageName;
    }
}

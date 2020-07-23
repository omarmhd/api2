<?php

namespace App\Http\Controllers\Api;

use App\DataCompany;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

   public function index(){


    $company=DB::table('data_company')->get();
    return response([
        'status' =>'success',
        'data' =>$company

    ]);
   }
   public function store(Request $request){


    $validator = Validator::make($request->all(), [
        'name_company' => 'required',
        'license_number' => 'required',
        'address' => 'required',
        'phone_number' => 'required|Numeric',
        'image' => 'required',

    ]);

    if ($validator->fails()) {
        return response([
        'status'=>'errors',
        'errors'=>$validator->errors()

        ]);
    }

           if(!empty(DataCompany::find(1))){


            $company_up=DataCompany::where('id','1')->update([

                'name_company' => $request->name_company,
                'license_number' => $request->license_number,
                'address' => $request->address,
                'phone_number'=>$request->phone_number,
                'image'=>$request->image

            ]);
            return response([
                'status'=>'success',
                'data'=>DataCompany::find(1)

                ]);

        }else{
    $company=DataCompany::create([
    'id'=>'1',
    'name_company' => $request->name_company,
    'license_number' => $request->license_number,
    'address' => $request->address,
    'phone_number'=>$request->phone_number,
    'image'=>$request->image,]);
    // $company  = DB::table('data_company')->updateOrInsert([
    //     'name_company' => $request->name_company,
    //     'license_number' => $request->license_number,
    //     'address' => $request->address,
    //     'phone_number'=>$request->address,
    //     'image'=>$request->image,



    //  ]);
    return response([
        'status'=>'success',
        'data'=>$company

        ]);}
   }
    public function show(){

    }
     public function update(){

     }
     public function destroy (){

     }
}

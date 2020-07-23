<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){


     $Users=User::where('Role','=','broker')->get();
        return response([
            'status' =>'success',
            'data' => $Users

        ]);
       }
       public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'phone' => 'required|Numeric',
            'date_work' => 'required|date',
            'address' => 'required',
            'Commission' => 'required',
            'image' => 'required',


        ]);

        if ($validator->fails()) {
            return response([
            'status'=>'errors',
            'errors'=>$validator->errors()

            ]);
        }

                $Users=User::create([

                    'name' => $request->name,
                    'password'=>$request->password,
                    'Role' =>$request->Role ?? 'broker',
                    'date_work' => $request->date_work,
                    'address'=>$request->address,
                    'phone'=>$request->phone,
                    'Commission'=>$request->Commission,
                    'image'=>$request->image

                ]);
                return response([
                    'status'=>'success',
                    'data'=>$Users

                    ]);

                }
        public function show(){

        }
         public function update(Request $request,$id){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',

            'phone' => 'required|Numeric',
            'date_work' => 'required|`date`',
            'address' => 'required',
            'Commission' => 'required',
            'image' => 'required',


        ]);

        if ($validator->fails()) {
            return response([
            'status'=>'errors',
            'errors'=>$validator->errors()

            ]);
        }

                $Users=User::find($id)->update([

                    'name' => $request->name,
                    'password'=>$request->password,
                    'Role' =>$request->Role,

                    'date_work' => $request->date_work,
                    'address'=>$request->address,
                    'phone'=>$request->phone,
                    'Commission'=>$request->Commission,
                    'image'=>$request->image

                ]);
                return response([
                    'status'=>'success',
                    'data'=>User::find($id)

                    ]);

         }
         public function destroy ($id){



            User::find($id)->destroy;
            return response([
                'status'=>'success',

                ]);
         }
    }


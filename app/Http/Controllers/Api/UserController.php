<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(){


     $Users=User::where('Role','=','2')->get();
     return UserResource::collection($Users);

       }
       public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'password' => 'required',
            'phone' => 'required|Numeric',
            'date_work' => 'required|date',
            'address' => 'required',
            'Commission' => 'required|Numeric',
            'image' => 'required',


        ]);

        if ($validator->fails()) {
            return response([
            'status'=>'errors',
            'errors'=>$validator->errors()

            ]);
        }
        $uplodeimge = $request->file('image');
        $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
        $uplodeimge->move('upload_images', $imageName);

        $Users=User::create([

                    'name' => $request->name,
                    'password'=>Hash::make($request->password),
                    'Role' =>$request->Role ?? '2',
                    'date_work' => $request->date_work,
                    'address'=>$request->address,
                    'api_token'=>Str::random(60),

                    'phone'=>$request->phone,
                    'Commission'=>$request->Commission,
                    'image'=> $imageName

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

                $Users=User::find($id)->update([

                    'name' => $request->name,
                    'password'=> Hash::make($request->password),
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



            $user=User::where('id',$id)->delete();
            if($user){
            return response([
                'status'=>'success',
                'message'=>'تم الحذف بنجاح ',

                ]);}
         }
    }


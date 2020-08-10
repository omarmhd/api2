<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlanResource;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
        $plan = Plan::all();
              return PlanResource::collection($plan);
    }


    public function show($id)
    {
        return response([
            'status' => 'success',
            'data' => Plan::where('type_id',$id)->get()
        ]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types'], ['name.required' => 'الرجاء إدخال إسم المخطط']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $plan = new Plan();

            $plan->name =  $request->name;
            $plan->type_id= $request->type_id;
            if ($file=$request->hasFile('image')) {

                $plan->image= $this->upload_image($file);

            }
            $plan->save();


        return response([
            'status' => 'success',
            'data' => $plan
        ]);
    }
    public function update(Request $request, $id)
    {

        $plan = Plan::find($id);

        $plan->name =  $request->name;
        if ($file=$request->hasFile('image')) {

            $plan->image= $this->upload_image($file);

        }
        $plan->save();

        return response([
            'status' => 'نجاح ',
            'data' => $plan
        ]);
    }


    public function destroy(Request $request, $id){

        $Plan=Plan::find($id)->delete();

        if($Plan){

            return response([
                'status'=>'نجاح ',
                'message'=>'تم الحذف بنجاح ',


            ]);

        }
    }


    public function upload_image($file){



        $imageName = time() . '.' .$file->getClientOriginalExtension();
        $file->move('upload_images', $imageName);

        return $imageName;
    }


}

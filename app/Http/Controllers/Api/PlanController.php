<?php

namespace App\Http\Controllers\Api;

use App\Eaqaar;
use App\Http\Resources\PlanResource;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

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
            'data' => Plan::where('type_id', $id)->get()
        ]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:plans'
        ], ['name.required' => 'الرجاء إدخال إسم المخطط']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $plan = new Plan();

        $plan->name =  $request->name;
        $plan->type_id = $request->type_id;
        if ($request->hasFile('image')) {

            $plan->image = asset('upload_images/'.$this->upload_image($request->file('image')));
        }
        $plan->save();


        return response([
            'status' => 'success',
            'data' => $plan
        ]);
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:plans,name,'.$id
        ], ['name.required' => 'الرجاء إدخال إسم المخطط']);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }

        $plan = Plan::find($id);
        $plan->name =  $request->name;
        if ($request->hasFile('image')) {
            $plan->image = asset('upload_images/'.$this->upload_image($request->file('image')));
        }
        $plan->save();

        return response([
            'status' => 'نجاح ',
            'data' => $plan
        ]);
    }


    public function destroy(Request $request, $id)
    {

        $Plan = Plan::find($id);


        $Eaqaar = Eaqaar::where("plan_id","=",$id)->get();

        $Eaqaar->toArray();
        if(!$Eaqaar->isEmpty()){

            return response([
                'status' => $Eaqaar,


            ]);        }


        if (file_exists($Plan->image)) {
            File::delete($Plan->image);
        }
        $Plan->delete();
        if ($Plan) {

            return response([
                'status' => 'نجاح ',
                'message' => 'تم الحذف بنجاح ',


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

<?php

namespace App\Http\Controllers\Api;

use App\DataCompany;
use App\Http\Controllers\Controller;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{

    public function index()
    {


        $types = Type::all();

        return response([
            'status' => 'success',
            'data' => $types


        ]);
    }

    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types',
            'image' => 'nullable|image',

        ], ['name.required' => 'الرجاء إدخال اسم النوع ',
        'image.image' => 'خطأ فى إدخال الصورة ',

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);
        }
        $Type=new Type();
        $Type->name=$request->name;

        if ($file=$request->File('image')) {

            $Type->image= $this->upload_image($file);

        }
        $Type->save();

        return response([
            'status' => 'success',
            'data' => $Type
        ]);
    }


    public function update(Request $request, $id)
    {

        $Type = Type::find($id);
        $Type->name=$request->name;

        if ($file=$request->File('image')) {

            $Type->image= $this->upload_image($file);

        }
        $Type->save();

        return response([
            'status' => 'نجاح ',
            'data' => $Type
        ]);
    }


    public function destroy(Request $request, $id){

        $type=Type::find($id)->delete();

        if($type){

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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {


        $Users = User::where('Role', '=', '2')->paginate(10);
        return UserResource::collection($Users);
    }
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'full_name' => 'required|unique:users',
            'login_name' => 'required|unique:users',
            'password' => 'required',
            'phone' => 'required|Numeric',
            'date_work' => 'required|date',
            'address' => 'required',
            'card'=>'required',
            'Commission' => 'required|Numeric',
            'image' => 'required|image',


        ], [
            'full_name.required' => 'الرجاء إدخال إسم السمسار ',
            'full_name.unique' => 'السمسار موجود مسبقا  ',
            'login_name.required' => 'الرجاء إدخال إسم السمسار ',
            'login_name.unique' => 'السمسار موجود مسبقا',
            'password.required' => 'الرجاء إدخال كلمة السر',
            'phone.required' => 'الرجاء إدخال رقم الهاتف',
            'phone.Numeric' => 'خطأ فى إدخال رفم الهاتف    ',
            'date_work.date' => 'خطأ فى  إدخال التاريخ ',
            'date_work.required' => 'الرجاء إدخال  التاريخ ',

            'address.required' => 'الرجاء إدخال العنوان ',
            'Commission.required' => 'الرجاء إدخال العمولة ',
            'Commission.Numeric' => 'خطأ فى إدخال قيمة العمولة ',

            'image.required' => 'الرجاء إدخال صورة السمسار ',
            'image.image' => 'خطأ فى  إدخال صورة السمسار '

        ],);

        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }
        $uplodeimge = $request->file('image');
        $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
        $uplodeimge->move('upload_images', $imageName);

        $Users = User::create([

            'full_name' => $request->full_name,
            'login_name' => $request->login_name,
            'password' => Hash::make($request->password),
            'Role' => $request->Role ?? '2',
            'date_work' => $request->date_work,
            'address' => $request->address,
            'api_token' => Str::random(60),
              'card'=> $request->address,
            'phone' => $request->phone,
            'Commission' => $request->Commission,
            'image' => $imageName

        ]);
        return response([
            'status' => 'تمت بنجاح ',
            'data' => $Users

        ]);
    }
    public function show()
    {
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [

            'full_name' => 'required|unique:users',
            'login_name' => 'required|unique:users',
            'password' => 'required',
            'phone' => 'required|Numeric',
            'card'=>'required',

            'date_work' => 'required|date',
            'address' => 'required',
            'Commission' => 'required|Numeric',
            'image' => 'required|image',


        ], [
            'full_name.required' => 'الرجاء إدخال إسم السمسار ',
            'full_name.unique' => 'السمسار موجود مسبقا  ',
            'login_name.required' => 'الرجاء إدخال إسم السمسار ',
            'login_name.unique' => 'السمسار موجود مسبقا',
            'password.required' => 'الرجاء إدخال كلمة السر',
            'phone.required' => 'الرجاء إدخال رقم الهاتف',
            'phone.Numeric' => 'خطأ فى إدخال رفم الهاتف    ',
            'date_work.date' => 'خطأ فى  إدخال التاريخ ',
            'date_work.required' => 'الرجاء إدخال  التاريخ ',

            'address.required' => 'الرجاء إدخال العنوان ',
            'Commission.required' => 'الرجاء إدخال العمولة ',
            'Commission.Numeric' => 'خطأ فى إدخال قيمة العمولة ',

            'image.required' => 'الرجاء إدخال صورة السمسار ',
            'image.image' => 'خطأ فى  إدخال صورة السمسار '

        ],);
        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }

        $Users = User::find($id)->update([

            'full_name' => $request->full_name,
            'login_name' => $request->login_name,
            'password' => Hash::make($request->password),
            'Role' => $request->Role,
            'date_work' => $request->date_work,
            'address' => $request->address,
            'phone' => $request->phone,
            'card'=> $request->address,
            'Commission' => $request->Commission,
            'image' => $request->image

        ]);
        return response([
            'status' => 'تمت بنجاح',
            'data' => User::find($id)

        ]);
    }
    public function destroy($id)
    {

        $user = User::where('id', $id)->first();


        $image_path = public_path('upload_images') . '/' . $user->image;

        if (file_exists($image_path)) {
            File::delete($image_path);
        }
        $user->delete();
        if ($user) {
            return response([
                'status' => 'نجاح ',
                'message' => 'تم الحذف بنجاح ',

            ]);
        }
    }
}

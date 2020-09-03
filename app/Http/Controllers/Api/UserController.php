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

    public function all_users()
    {


        $Users = User::paginate(10);
        return UserResource::collection($Users);
    }
    public function profile()
    {

        $Users = User::where('id', '=',  auth('api')->user()->id)->get();
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
            'card' => 'required',
            'Commission' => 'required|Numeric',
            'account_number' => 'required|Numeric',
            'image' => 'nullable|image',


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
            'account_number.Numeric' => 'خطأ فى إدخال رقم الحساب  ',
            'account_number.required' => 'الرجاء إدخال رقم الحساب',
            'image.required' => 'الرجاء إدخال صورة السمسار ',
            'image.image' => 'خطأ فى  إدخال صورة السمسار '

        ],);

        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }

        if ($file = $request->file('image')) {
            $uplodeimge = $request->file('image');
            $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
            $uplodeimge->move('upload_images', $imageName);
        }
        $Users = User::create([

            'full_name' => $request->full_name,
            'login_name' => $request->login_name,
            'password' => Hash::make($request->password),
            'Role' => $request->Role ?? '2',
            'date_work' => $request->date_work,
            'address' => $request->address,
            'card' => $request->card,
            'phone' => $request->phone,
            'Commission' => $request->Commission,
            'account_number' => $request->account_number,
            'image' => $imageName ?? 'null'
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

            'full_name' => 'sometimes|unique:users,full_name,' . $id,
            'login_name' => 'sometimes|unique:users,login_name,' . $id,
            'password' => 'required',
            'phone' => 'required|Numeric',
            'card' => 'required',
            'date_work' => 'required|date',
            'address' => 'required',
            'Commission' => 'required|Numeric',
            'image' => 'nullable|image',
            'account_number' => 'required|Numeric'
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
            'account_number.Numeric' => 'خطأ فى إدخال رقم الحساب  ',
            'account_number.required' => 'الرجاء إدخال قيمة العمولة ',
            'image.required' => 'الرجاء إدخال صورة السمسار ',
            'image.image' => 'خطأ فى  إدخال صورة السمسار '

        ],);
        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }

        $Users = User::find($id);

        $Users->full_name = $request->full_name;
        $Users->login_name = $request->login_name;
        $Users->password = Hash::make($request->password);
        $Users->Role = $request->Role;
        $Users->date_work = $request->date_work;
        $Users->address = $request->address;
        $Users->phone = $request->phone;
        $Users->account_number = $request->account_number;
        $Users->card = $request->card;
        $Users->Commission = $request->Commission;
        $Users->number_deals=$request->number_deals;
        $Users->profit_broker=$request->profit_broker;
        $Users->Profit_Company=$request->Profit_Company;

        if ($file = $request->file('image')) {
            $uplodeimge = $request->file('image');
            $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
            $uplodeimge->move('upload_images', $imageName);
            $Users->image = $imageName;
        }
        $Users->save();

        return  UserResource::collection(User::where('id', $id)->get());
    }

    public function Best_seller(Request $request)
    {

        if ($request->type_show == 1) {
            $Best_seller =  User::where('Role', '=', '2')->orderBy('number_deals', 'desc')->paginate(10);
            return UserResource::collection($Best_seller);
        }
        if ($request->type_show == 2) {
            $Best_seller = User::where('Role', '=', '2')->orderBy('Profit_Company', 'desc')->paginate(10);
            return UserResource::collection($Best_seller);
        }
        if ($request->type_show == 3) {
            $Best_seller = User::where('Role', '=', '2')->orderBy('profit_broker', 'desc')->paginate(10);
            return UserResource::collection($Best_seller);
        }
        return null;
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

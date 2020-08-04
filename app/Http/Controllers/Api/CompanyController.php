<?php

namespace App\Http\Controllers\Api;

use App\DataCompany;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function index()
    {


        $company = DB::table('data_company')->get();
        return CompanyResource::collection($company);
    }
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name_company' => 'required',
            'license_number' => 'required',
            'address' => 'required',
            'phone_number' => 'required|Numeric',
            'image' => 'nullable|image',
            'details' => 'required'

        ], [
            'name_company.required' => 'الرجاءإدخال اسم الشركة ',
            'license_number.required' => 'الرجاءإدخال رقم الترخيص ',
            'address.required' => 'الرجاء إدخال عنوان الشركة ',
            'phone_number.required' => 'الرجاءإدخال رقم الموبايل',
            'phone_number.Numeric' => ' خطأ فى إدخال رقم الموبايل',
            'image.image' => 'خطأ فى إدخال الصورة ',
            'details.required' => 'الرجاء إدخال التفاصيل'


        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'خطأ',
                'errors' => $validator->errors()

            ]);
        }

        if (!empty(DataCompany::find(1))) {

            $company_up = DataCompany::find(1);
            $company_up->name_company = $request->name_company;
            $company_up->license_number = $request->license_number;
            $company_up->address = $request->address;
            $company_up->phone_number = $request->phone_number;
            $company_up->details = $request->details;
            if ($request->hasFile('image')) {
                $uplodeimge = $request->file('image');
                $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
                $uplodeimge->move('upload_images', $imageName);
                $company_up->image = $imageName;

            }
            $company_up->save();
            // return response([
            //     'status'=>'تم تحديث بيانات الشركة بنجاح ',
            //     'data'=>

            //     ]);
            return CompanyResource::collection(  DataCompany::find(1)->get());
        } else {
               $company = new DataCompany;
               $company->name_company = $request->name_company;
               $company->license_number = $request->license_number;
               $company->address = $request->address;
               $company->phone_number = $request->phone_number;
               $company->details = $request->details;
               if ($request->hasFile('image')) {
                   $uplodeimge = $request->file('image');
                   $imageName = time() . '.' . $uplodeimge->getClientOriginalExtension();
                   $uplodeimge->move('upload_images', $imageName);
                   $company->image = $imageName;

               }
               $company->save();
            // $company  = DB::table('data_company')->updateOrInsert([
            //     'name_company' => $request->name_company,
            //     'license_number' => $request->license_number,
            //     'address' => $request->address,
            //     'phone_number'=>$request->address,
            //     'image'=>$request->image,



            //  ]);
            if (!empty($company)) {
                return CompanyResource::collection( DataCompany::find(1)->get());
            }
        }
    }
    public function show()
    {
    }
    public function update()
    {
    }
    public function destroy($id)
    {
        DataCompany::find(1)->delete();
    }
}

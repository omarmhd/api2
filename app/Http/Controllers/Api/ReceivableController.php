<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EaqaarResource;
use App\Http\Resources\ReceivableResource;
use App\Receivable;
use Illuminate\Support\Facades\Validator;

class ReceivableController extends Controller
{

    public function index()
    {


        return ReceivableResource::collection(Receivable::all());
    }

    public function from_to(Request $request)
    {

        $return_paginate = $request->return_paginate;

        $validator = Validator::make($request->all(), [
            'from' => 'required|date',
            'to' => 'required|date',

        ], [
            'from.required' => 'الرجاء إدخال التاريخ',
            'from.date' => 'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
            'to.required' => 'الرجاء إدخال التاريخ',
            'to.date' => 'الرجاء إدخال التاريخ بالصيغة الصحيحة ',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'errors',
                'errors' => $validator->errors()

            ]);


        }
        $from = $request->from;
        $to = $request->to;

        if ($return_paginate == 1) {
            $Receivables = Receivable::whereBetween('date', [$from,$to])->where('type',$request->type)->paginate(10);
            return ReceivableResource::collection($Receivables);
        } else {
            $Receivables = Receivable::whereBetween('date', [$from,$to])->get();
            return ReceivableResource::collection($Receivables);
        }
    }

    public function destroy($id)
    {

        $Receivables = Receivable::find($id)->delete();

        if ($Receivables) {
            return response([
                'status' => 'تم حذف العقار بنجاح ',
            ]);
        }
    }
}

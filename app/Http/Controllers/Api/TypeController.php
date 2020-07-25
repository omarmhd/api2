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

    public function index(){


        $types=Type::all();

        return response([
       'status'=>'success',
       'data'=>$types


        ]);


    }

}

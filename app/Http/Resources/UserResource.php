<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        if($this->image=='null'){


            $img =$this->image;
        }else{


           $img= asset('upload_images/'.$this->image);
        }


        $sold_eaqaars=User::find(1);
        return ([
         'status'=>'success',
         'data'=>[
          'login_name'=>$this->login_name,
          'full_name'=>$this->full_name,
          'Role'=>$this->Role,
          'account_type'=>$this->account_type,
          'date_work'=>$this->date_work,
          'address'=>$this->address,
          'phone'=>$this->phone,
          'Commission'=>$this->Commission,
          'purchase_commission'=>$this->purchase_commission,
          'card'=>$this->card,
          'account_number'=>$this->account_number,
          'image_path'=>$img ,
          'number_deals'=>$this->number_deals,
          'Profit_Company'=>$this->Profit_Company,
          'profit_broker'=>$this->profit_broker,
          'id'=>$this->id



          ]

        ]);
    }
}

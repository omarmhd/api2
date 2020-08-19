<?php

namespace App\Http\Resources;

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


        return ([
            'status'=>'success',
            'data'=>[
          'login_name'=>$this->login_name,
          'full_name'=>$this->full_name,
          'Role'=>$this->Role,
          'date_work'=>$this->date_work,
          'address'=>$this->address,
          'phone'=>$this->phone,
          'Commission'=>$this->Commission,
          'card'=>$this->card,
          'account_number'=>$this->account_number,
          'image_path'=>$img ,
          'url'=>$this->url,
          'id'=>$this->id]

        ]);
    }
}

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
        return ([
          'login_name'=>$this->login_name,
          'full_name'=>$this->full_name,
          'Role'=>$this->Role,
          'date_work'=>$this->date_work,
          'address'=>$this->address,
          'phone'=>$this->phone,
          'Commission'=>$this->Commission,
          'card'=>$this->card,
          'account_number'=>$this->account_number,
          'image_path'=>  asset('upload_images/'.$this->image) ,
          'id'=>$this->id

        ]);
    }
}

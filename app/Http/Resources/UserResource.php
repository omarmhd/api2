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
          'name'=>$this->name,
          'Role'=>$this->Role,
          'date_work'=>$this->date_work,
          'address'=>$this->address,
          'api_token'=>$this->api_token,
          'phone'=>$this->phone,
          'Commission'=>$this->Commission,
          'image_path'=>public_path('upload_images').'/'.$this->image,
          'id'=>$this->id

        ]);
    }
}

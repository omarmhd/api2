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
          'Role'=>$this->name,
          'date_work'=>$this->name,
          'address'=>$this->name,
          'api_token'=>$this->name,
          'phone'=>$this->name,
          'Commission'=>$this->name,
          'image_path'=>public_path('upload_images').'/'.$this->image,
          'id'=>$this->id

        ]);
    }
}

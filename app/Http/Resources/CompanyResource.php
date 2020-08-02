<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return (
            [
              'name_company'=>$this->name_company,
              'license_number'=>$this->license_number,
              'address'=>$this->address,
              'phone_number'=>$this->phone_number,
              'image'=> asset('upload_images/'.$this->image),

            ]);
    }
}

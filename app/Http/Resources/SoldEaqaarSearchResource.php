<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldEaqaarSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[

            'state'=>$this->state,
            'area'=>$this->area,
            'square'=>$this->square,
            'Part_number'=>$this->Part_number,
            'space'=>$this->space,
            'Survey_number'=>$this->Survey_number


        ];
    }
}

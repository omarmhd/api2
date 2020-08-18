<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceivableResource extends JsonResource
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
            'id'=>$this->id,
            'user_name'=>$this->user_name,
            'type'=>$this->type,
            'date'=>$this->date,
            'Remaining_amount'=>$this->Remaining_amount


            ]);
    }
}

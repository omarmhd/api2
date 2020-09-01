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
        return [
            'id'=>$this->id,
            'user_name'=>$this->user_name,
            'user'=>$this->user,

            'type'=>$this->type,
            'date'=>$this->date,
            'Remaining_amount'=>$this->Remaining_amount,
            'eaqaar'=>['state'=>$this->eaqqar,
            'area'=>$this->eaqqar->area,
            'square'=>$this->eaqqar->square,
            'Part_number'=>$this->eaqqar->Part_number,
            'space'=>$this->eaqqar->space,
            'Survey_number'=>$this->eaqqar->Survey_number,]

            ];
    }
}

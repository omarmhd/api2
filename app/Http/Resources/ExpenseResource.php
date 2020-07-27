<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'amount'=>$this->amount,
        'details'=>$this->details,
            'date'=>$this->date,
            'type'=>$this->type,

            'id'=>$this->id

          ]);    }
}

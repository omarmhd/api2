<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name','type_id','count'];


    public  function type(){

   return $this->belongsTo('App\Type');

    }

}

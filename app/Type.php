<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name','image'];




public function registrationEaqaars(){

    $this->hasMany('App\RegistrationEaqaar');

    }

    public function plans(){

        return $this->hasMany('App\plan');

        }
}

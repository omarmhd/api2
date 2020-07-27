<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{

public function registrationEaqaars(){

    $this->hasMany('App\RegistrationEaqaar');

    }

    public function plans(){

        return $this->hasMany('App\plan');

        }
}

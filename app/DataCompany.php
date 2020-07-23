<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataCompany extends Model
{
    public $table = 'data_company';
    protected $fillable = ['name_company','license_number','address','phone_number','image','created_at','updated_at
    '];


}

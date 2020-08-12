<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $fillable = ['user_name','Remaining_amount','eaqaar_id','type','date'];
}

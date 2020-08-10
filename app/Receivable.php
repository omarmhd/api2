<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $fillable = ['Remaining_amount','eaqaar_id','type','date'];
}

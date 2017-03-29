<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    public function user(){

        return $this->belongsTo('App\User','doctor_id','id');
    }
}

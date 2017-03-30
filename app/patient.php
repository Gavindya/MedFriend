<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    protected $fillable = [
        'patient_id','title', 'allergy',
    ];
    public function user(){

        return $this->belongsTo('App\User','patient_id','id');
    }
}

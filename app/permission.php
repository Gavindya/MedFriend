<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    protected $fillable = [
        'doctor_ID','patient_ID','field_ID','request',
        'status',
    ];

    public function patient(){
        return $this->belongsTo('App\patient','patient_ID','patient_id');
    }
    public function doctor(){
        return $this->belongsTo('App\doctor','doctor_ID','doctor_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class family extends Model
{
//    protected $table = 'families';
    protected $fillable = [
        'patient_ID','family_id',
    ];

    public function family_member(){

        return $this->belongsTo('App\patient','family_id','patient_id');
    }
    public function patient(){

        return $this->belongsTo('App\patient','patient_ID','patient_id');
    }
}

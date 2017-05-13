<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'patient_id','title', 'birthday',
        'gender','nationality', 'blood_group',
        'height','weight', 'hair_color',
        'eye_color','mobile',

    ];

    //patient is a user -join patient and user table
    public function user(){
        return $this->belongsTo('App\User','patient_id','id');
    }
}

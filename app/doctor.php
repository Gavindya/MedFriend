<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'doctor_id','title', 'specialization_field_id',
        'regNumber','mobile',

    ];

    //doctor is a user-join doctor and user table
    public function user(){
        return $this->belongsTo('App\User','doctor_id','id');
    }
}

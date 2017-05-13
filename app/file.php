<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file extends Model
{
    protected $fillable = [
        'patient_ID','specialization_ID','file_name','description','extension',
    ];

}

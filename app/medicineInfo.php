<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class medicineInfo extends Model
{
    protected $fillable = [
        'patient_ID','medicine', 'info',
    ];
}

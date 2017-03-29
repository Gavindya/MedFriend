<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class allergy extends Model
{
    protected $fillable = [
        'patient_ID','medicine', 'allergy',
    ];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bloodDonated extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'patient_ID','donated', 
    ];
}

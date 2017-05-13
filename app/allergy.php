<?php

namespace App;

use App\Http\Controllers\LabController;
use Illuminate\Database\Eloquent\Model;

class allergy extends Model
{
    protected $fillable = [
        'patient_ID','medicine', 'allergy',
    ];

//    public function something(){
//        $lb = new LabController();
////        $r = $lb->someVal;
//    }
}

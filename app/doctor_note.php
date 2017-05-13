<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doctor_note extends Model
{
    protected $fillable = [
        'file_ID','doctor_ID', 'note',
    ];
    
    public function doctor(){
        return $this->belongsTo('App\doctor','doctor_ID','doctor_id');
    }
}

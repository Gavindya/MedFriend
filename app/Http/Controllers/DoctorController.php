<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function doctorHome(){
        return view('doctor.doctorHome');
    }
}

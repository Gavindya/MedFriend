<?php

namespace App\Http\Controllers;

use App\role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function doctorHome(){
        $doctor_id = Auth::user()->id;
        echo dd($doctor_id);
//        $roles = role::all();
//        echo dd(json_encode(json_decode($roles),true));
//        $notifications =
        return view('doctor.doctorHome');
    }
}

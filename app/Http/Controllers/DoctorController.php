<?php

namespace App\Http\Controllers;

use App\permission;
use App\role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function doctorHome(){
        $doctor_id = Auth::user()->id;
        $waiting= permission::all()->where('doctor_ID',$doctor_id)->where('status',0)->sortByDesc('updated_at');
        $activePatients= permission::all()->where('doctor_ID',$doctor_id)->where('status',1)->sortByDesc('updated_at');
//        echo dd($activePatients[0]->patient->user->name);
        $expiredPatients= permission::all()->where('doctor_ID',$doctor_id)->where('status',2)->sortByDesc('updated_at');

        return view('doctor.doctorHome',
            [
                'waiting'=>$waiting,
                'active'=>$activePatients,
                'expired'=>$expiredPatients
            ]);
    }

    public function getSelectedPatient(Request $request){
//        echo dd($request->all()['$value']);
        $keyword = $request->all()['$value'];
        if ($request->ajax()){
            $output = "";
            $patient =User::all()->where('id',$keyword);
//            echo dd($patient->first());
            if($patient)
            {
                $output='<h3'.'>'.$patient->first()['id'].'</h3>'.
                '<h3'.'>'.$patient->first()['name'].'</h3>'.
                '<h3'.'>'.$patient->first()['middle_name'].'</h3>'.
                '<h3'.'>'.$patient->first()['last_name'].'</h3>'.
                '<div><a class="btn btn-success" href="uploadFilePage/'.$patient->first()->id.'">View Records</a></div>';
                return Response($output);
            }
        }
    }
    
    public function test(){
        header("Access-Control-Allow-Origin: *");
        $data = array(
            array('id' => '1','first_name' => 'Cynthia'),
            array('id' => '2','first_name' => 'Keith'),
            array('id' => '3','first_name' => 'Robert'),
            array('id' => '4','first_name' => 'Theresa'),
            array('id' => '5','first_name' => 'Margaret')
        );

        echo json_encode($data);
    }


}

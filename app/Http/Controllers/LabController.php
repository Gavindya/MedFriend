<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function searchPatients(Request $request){
//        echo ($request->all()['$value']);
        $keyword = $request->all()['$value'];
        if ($request->ajax()){
            $output = "";

            $patients =User::all()->where('role_id',2);
            if($patients)
            {

                foreach ($patients as $patient)
                {
//                    <li><a href="{{url('uploadFilePage/$patient->id')}}">$patient->name<br/></a></li>
                    $output.='<li'.'><a href="uploadFilePage/'.$patient->id.'">'.$patient->name.'</a></li>';
                }
                return Response($output);
            }

        }
    }

    public function labHomePage(){
        return view('labAssistant.labHomePage',['patients'=>null]);
    }

    public function uploadFilePage($id){
        return view('labAssistant.uploadFilePage',['patientID'=>$id]);
    }

    public function uploadFile(Request $request){
//        echo dd($request->all());
        $this->validate($request, [
            'doc' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);
        //***************************STORE IN PUBLIC/UPLOADS FOLDER**************
        //should get id of the entered member****IMPORTANT!!!!!
        $fileName =$request->all()['patient_id'].'.'.$request->file('doc')->getClientOriginalExtension();
        $request->file('doc')->move(public_path('records'), $fileName);
//        $request->file('image')->move(public_path('uploads'), $imageName);

        //***************************STORE IN STORAGE/APP/UPLOADS FOLDER******************************************
//        $imageName =member::all()->count().'.'.$request->file('image')->getClientOriginalExtension();
//        $request->file('image')->storePubliclyAs('/uploads',$imageName);

//        $proPic = new image();
//        $proPic->size = $request->file('image')->getSize();
//        $proPic->type = $request->file('image')->getClientOriginalExtension();
//        $proPic->member_id = member::all()->count();
//        $proPic->name = $request->all()['first_name'].'.'.$request->all()['last_name'];
//        $proPic->save();


    }
}

<?php

namespace App\Http\Controllers;

use App\doctor;
use App\doctor_note;
use App\patient;
use App\permission;
use App\privilege;
use App\specialization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Session;

class DoctorController extends Controller
{
    public function doctorHome(){
        //EVERY TIME LOADING HOME CHECK FOR NOTIFICATIONS OF ACCEPTED REQUESTS

        //redirect to home page of doctor giving recent associated data with the patients
        $doctor_id = Auth::user()->id;
        //waiting - patients for permission to view records
        $waiting= permission::all()->where('doctor_ID',$doctor_id)->where('status',0)->sortByDesc('updated_at');
        //active - currently has permission
        $activePatients= permission::all()->where('doctor_ID',$doctor_id)->where('status',1)->sortByDesc('updated_at');
//        echo dd($activePatients[0]->patient->user->name);
        
        //expired- permission timed out
        $expiredPatients= permission::all()->where('doctor_ID',$doctor_id)->where('status',2)->sortByDesc('updated_at');

        return view('doctor.doctorHome',
            [
                'waiting'=>$waiting,
                'active'=>$activePatients,
                'expired'=>$expiredPatients
            ]);
    }


    public function doc_searchPatients(Request $request){
        $keyword = $request->all()['$value'];  //ajax request sent with parameter $value which contains the string to be searched
        if ($request->ajax()){
            $output = "";
            $patients = User::all()->where('role_id',2);
            if($patients)
            {
                foreach ($patients as $patient)
                {
                    $output.='<li'.'><a href="viewPatient/'.$patient->id.'">'.$patient->name.'</a></li>';
                }
                return Response($output);
            }

        }
    }

    public function getSelectedPatient(Request $request){

        $keyword = $request->all()['$value'];
        if ($request->ajax()){
            $output = "";
            $patient =User::all()->where('id',$keyword);
            if($patient)
            {
                $output='<h3'.'>'.$patient->first()['id'].'</h3>'.
                '<h3'.'>'.$patient->first()['name'].'</h3>'.
                '<h3'.'>'.$patient->first()['middle_name'].'</h3>'.
                '<h3'.'>'.$patient->first()['last_name'].'</h3>'.
                '<div><a class="btn btn-success" href="viewPatient/'.$patient->first()->id.'">View Records</a></div>';
                return Response($output);
            }
        }
    }
    public function viewPatient($id){
        $doctor_id=Auth::user()->id;
        $patient_id=$id;
        $doctor= doctor::all()->where('doctor_id',$doctor_id)->first();
        $specialization_field_id = $doctor->specialization_field_id;

        $patient = patient::with('user')->where('patient_id',$id)->get()->first();
        $fields = specialization::all();

        $FieldsList = array();
        array_push($FieldsList,$specialization_field_id);
        $privilegeRows = privilege::all()->where('doctor_ID',$doctor_id);
        if(!$privilegeRows==null){
            foreach ($privilegeRows as $pr){
                array_push($FieldsList,$pr->specialization_field_id);
            }
        }
        $permissionedRows = permission::all()->where('doctor_ID',$doctor_id)->where('patient_ID',$patient_id)
            ->where('status',1);
        if(!$permissionedRows==null){
            foreach ($permissionedRows as $perR){
                array_push($FieldsList,$perR->field_ID);
            }
        }
        return view('doctor.patientPage',
            [
                'patient'=>$patient,
                'fields'=>$fields,
                'doctor_id'=>$doctor_id,
                'specialization_field_id'=>$specialization_field_id,
                'allowedFieldsList'=>$FieldsList
            ]);
        
    }
    public function viewReport(Request $request){
        
        $patient_id =$request->all()['user_id'];
        $doctor_id = $request->all()['doctor_id'];
        $specialization_field_id = $request->all()['specialization_field_id'];
//        $field = specialization::all()->where('')
//        $path = storage_path('app/records/patient_ID-'.$patient_id.'/'.$section);

        $FieldsList = array();
        array_push($FieldsList,$specialization_field_id);
        $privilegeRows = privilege::all()->where('doctor_ID',$doctor_id);
        if(!$privilegeRows==null){
            foreach ($privilegeRows as $pr){
                array_push($FieldsList,$pr->specialization_field_id);
            }
        }
        $permissionedRows = permission::all()->where('doctor_ID',$doctor_id)->where('patient_ID',$patient_id)
            ->where('status',1);
        if(!$permissionedRows==null){
            foreach ($permissionedRows as $perR){
                array_push($FieldsList,$perR->field_ID);
            }
        }
        $FieldNameList = array();
        foreach ($FieldsList as $fl){
            $f = specialization::all()->where('id',$fl)->first();
            array_push($FieldNameList,$f);
        }
        return view('doctor.listOfAllowedFields',
            [
                'doctor_id'=>$doctor_id,
                'patient_id'=>$patient_id,
                'FieldNameList'=>$FieldNameList
            ]);

    }
    public function listOutRecords($patient_id,$specialization_field_id){
        $path = storage_path('app/records/patient_ID-'.$patient_id.'/'.$specialization_field_id);
        if(!file_exists($path)){
            echo dd("not found");
        }else{
            $files = File::allFiles($path); //set of objects of type splFileinfo. can use methods of that cls ie getPathName()
            $fileNames = array();
            $reports = array();
            foreach ($files as $f){
                $file_name = $f->getFileName();
                $file = \App\file::all()->where('patient_ID',$patient_id)
                    ->where('specialization_ID',$specialization_field_id)
                    ->where('file_name',$file_name)->first();

                if($file!=null){
                    $notes = doctor_note::all()->where('file_ID',$file->file_id);
                    $doctor_notes = array();

                    if($notes!=null){
                        foreach ($notes as $note){
                            $doctor = doctor::with('user')->where('doctor_id',$note->doctor_ID)->get()->first();
                            $NOTE = array('note'=>$note->note,'doctor'=>$doctor->user->name.' '.$doctor->user->last_name);
                            array_push($doctor_notes,$NOTE);
                        }
                    }
                    $fileDetails = array('fileID'=>$file->file_id,'fileName'=>$file_name,
                        'description'=>$file->description,'notes'=>$doctor_notes);

                    array_push($reports,$fileDetails);
                }
                array_push($fileNames,$f->getFileName());
            }
        }
        return view('doctor.reportsList',
            [
                'user_id'=>$patient_id,
                'field_id'=>$specialization_field_id,
                'fileNames'=>$fileNames,
                'reports'=>$reports
            ]);
    }

    public function view($user,$field,$file){
        
        $path = storage_path('app/records/patient_ID-'.$user.'/'.$field.'/'.$file);
        
        if(!file_exists($path)){
            echo dd("not found");
        }
        else
        {
            $file = file_get_contents($path);
            $type= mime_content_type($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
    }
    public function addNotes(Request $request){
        $note = new doctor_note();
        $note->file_ID = $request->all()['file_id'];
        $note->doctor_ID = Auth::user()->id;
        $note->note = $request->all()['note'];
        $note->save();
        return redirect()->back();
    }
    public function askPermissionPage($doctor_id){
        echo dd($doctor_id);
    }
    public function askPermission($doctor_id,$patient_id,$requesting_field_id){
        $field = specialization::all()->where('id',$requesting_field_id)->first();
        $fieldName = $field->field;
        $permission = new permission();
        $permission->doctor_ID = $doctor_id;
        $permission->patient_ID = $patient_id;
        $permission->field_ID = $requesting_field_id;
        $permission->request = 'I would like to view reports of '.$fieldName;
        $permission->status = 0;
        //status 0 when requested
        //status 1 when accepted
        //status 2 when timed out
        
        //here send a msg to doctor saying successfully recorded the msg
        if($permission->save()){
            Session::flash('requested', "Request sent!");
            Session::flash('alertType', "alert-success");
        }else{
            Session::flash('requested', "Error Occurred!");
            Session::flash('alertType', "alert-danger");
        }
        return redirect()->back();
    }

    
    //TEST FOR MOBILE APP
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

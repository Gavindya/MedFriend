<?php

namespace App\Http\Controllers;

use App\allergy;
use App\doctor;
use App\doctor_note;
use App\family;
use App\patient;
use App\medicineInfo;
use App\permission;
use App\specialization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class PatientController extends Controller
{
    public function getAllergies($id){
        header("Access-Control-Allow-Origin: *");
        $allergies = allergy::all()->where('patient_ID',$id)->all();
        //get allergies of the mobile app user
        $allegriesArray = array();
        foreach ($allergies as $a) {
            array_push($allegriesArray,$a);
        }
        echo json_encode($allegriesArray);
    }

    public function setAllergy (Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $allergy= new allergy();                                        //record new allergy of the mobile app user
        $allergy->patient_ID = $request->all()['userid'];
        $allergy->medicine = $request->all()['medicine'];
        $allergy->allergy = $request->all()['allergy'];

        if($allergy->save()){                                         //if successfully saved, return all the allergies including new one
            $allergies = allergy::all()->where('patient_ID',$request->only('userid'));
            echo json_encode($allergies);
        }else{
            return response('not successful');
        }
    }
    public function getMedicineInfo($id){
        header("Access-Control-Allow-Origin: *");
        $medicineInfo = medicineInfo::all()->where('patient_ID',$id);   //get all the records on medicine of mobile app user
        echo json_encode($medicineInfo);
    }

    public function setMedicineInfo(Request $request){                  //record new information on medicine of the mobile app user
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $medicineInfo= new medicineInfo();
        $medicineInfo->patient_ID = $request->all()['userid'];
        $medicineInfo->medicine = $request->all()['medicine'];
        $medicineInfo->info = $request->all()['info'];

        if($medicineInfo->save()){                   //if successfully saved, return all the medicine records including new one
            $medicineInfo = medicineInfo::all()->where('patient_ID',$request->only('userid'));
            echo json_encode($medicineInfo);
        }else{
            return response('not successful');
        }
    }

    public function getExistingFamilyMembers($user_id){
        header("Access-Control-Allow-Origin: *");
        $family_members= family::all()->where('patient_ID',$user_id);   //get all family members' (id)listed
        $data = array();
        foreach ($family_members as $mem){
            $details = array('mem_first_name' =>$mem->family_member->user->name,'mem_middle_name' => $mem->family_member->user->middle_name,
                'mem_last_name' => $mem->family_member->user->last_name,'mem_dob' => $mem->family_member->birthday,
                'mem_gender' => $mem->family_member->gender,'mem_blood' => $mem->family_member->blood_group,
                'mem_id' => $mem->family_id,'mem_height' => $mem->family_member->height,'mem_weight' => $mem->family_member->weight,
                'mem_mobile' => $mem->family_member->mobile);
            array_push($data,$details);
        }
        return ($data);
    }

    public function getFamilyMembers($id){
        header("Access-Control-Allow-Origin: *");
        $family_members= family::all()->where('patient_ID',$id);   //get all family members' (id)listed

        $data = array();

        //get user information of family members using orm

        foreach ($family_members as $mem){
            $details = array('mem_first_name' =>$mem->family_member->user->name,'mem_middle_name' => $mem->family_member->user->middle_name,
                'mem_last_name' => $mem->family_member->user->last_name,'mem_dob' => $mem->family_member->birthday,
                'mem_gender' => $mem->family_member->gender,'mem_blood' => $mem->family_member->blood_group,
                'mem_id' => $mem->family_id,'mem_height' => $mem->family_member->height,'mem_weight' => $mem->family_member->weight,
                'mem_mobile' => $mem->family_member->mobile);
            array_push($data,$details);
        }
        echo json_encode($data);
    }

    public function setFamilyMember($user_id,$member_id){
        //NOT IMPLEMENTED YET

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
//        $fam = new family();
//        $fam->patient_ID = $user_id;
//        $fam->family_id = $member_id;
//        if($fam->save()){
        if(DB::table('families')->insert(['patient_ID' => $user_id, 'family_id' => $member_id])){
            $this->getFamilyMembers($user_id);
        }else{
            return json_encode("not authenticated");
        }
    }
    public function deleteFamilyMember($user_id,$member_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $family_member= family::all()->where('patient_ID',$user_id)->where('family_id',$member_id)->first();
        $fam_record = family::find($family_member->id);
        if($fam_record->delete()){
            $this->getFamilyMembers($user_id);
        }else{
            return json_encode("not authenticated");
        }

    }
    public function mobileSearchPatients($user_id,$name){
//        $family_members= family::all()->where('name','like', '%' . $name . '%');
//        $family_members= User::all()->where('role_id',2)->where('name','LIKE',"%{$name}%");
//        $family_members= User::all()->where('role_id',2);   //get all mobile users(patients) their role id is 2
        $family_members = patient::all();
        $dataArray = array();
//        $already_members = json_encode($this->getExistingFamilyMembers($user_id));
//        echo json_encode(sizeof($already_members));
//        $already_mem_ids=array();
//        foreach ($already_members as $am){
//            array_push($already_mem_ids,$am->mem_id);
//        }
//        echo json_encode(sizeof($already_mem_ids));

        foreach ($family_members as $mem){
            $user = User::all()->where('id',$mem->patient_id)->first();
//            if($user_id!=$mem->patient_id and !in_array($mem->patient_id, $already_mem_ids) ){
            if($user_id!=$mem->patient_id){
                //check whether input string contains in any of the names
                if( (strpos( $user->name, $name ) !== false) or
                    (strpos( $user->middle_name, $name ) !== false) or
                    (strpos( $user->last_name, $name ) !== false)){
                    $details = array('mem_id'=>$mem->patient_id,'mem_first_name' =>$user->name,
                        'mem_middle_name' => $user->middle_name,
                        'mem_last_name' => $user->last_name);
                    array_push($dataArray,$details);
                }
            }

        }
        echo json_encode($dataArray);
    }

    public function getProfileDetails($id){
        $user = patient::with('user')->where('patient_id',$id)->get()->first();  //get recorded data of the mobile user(patient)
        if($user!=null) {

            //return all the details of patient (do join patient table and user table)
            $patient = array('joined' => $user->user->created_at, 'first_name' => $user->user->name,
                'middle_name' => $user->user->middle_name,
                'last_name' => $user->user->last_name, 'dob' => $user->birthday,
                'gender' => $user->gender, 'blood' => $user->blood_group,
                'id' => $user->patient_id, 'heightOfPerson' => $user->height, 'weight' => $user->weight,
                'mobile' => $user->mobile, 'titleOfPerson' => $user->title, 'nationality' => $user->nationality,
                'hair_color' => $user->hair_color,
                'eye_color' => $user->eye_color,
                'record_id'=>$user->id);
            return response($patient);
        }else{
            return response("does not exist");
        }
    }
    public function setProfileDetails(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $member= new patient();
        $member->exists = true;                                 //update profile details of existing patient
        $member->id = $request->all()['record_id'];
        $member->patient_id = $request->all()['userid'];
        $member->weight = $request->all()['weight'];
        $member->height = $request->all()['heightOfPerson'];
        $member->hair_color = $request->all()['hair_color'];
        $member->eye_color = $request->all()['eye_color'];
        $member->mobile = $request->all()['mobile'];

        if($member->save()){
            $mem = patient::with('user')->where('patient_id',$request->only('userid'))->get()->first();
            $patient = array('joined'=>$mem->user->created_at,'first_name' =>$mem->user->name,'middle_name' => $mem->user->middle_name,
                'last_name' => $mem->user->last_name,'dob' => $mem->birthday,
                'gender' => $mem->gender,'blood' => $mem->blood_group,
                'id' => $mem->patient_id,'heightOfPerson' => $mem->height,'weight' => $mem->weight,
                'mobile' => $mem->mobile,'titleOfPerson'=>$mem->title,'nationality'=>$mem->nationality,
                'hair_color'=>$mem->hair_color,
                'eye_color'=>$mem->eye_color);
            return response($patient);
        }else{
            return response('not authenticated');
        }
    }

    public function mobileSearchDoctors($name){
        //NOT Implemented yet

        $doctors= doctor::all();
        $dataArray = array();
        foreach ($doctors as $doctor){
            if( (strpos( $doctor->user->name, $name ) !== false) or
                (strpos( $doctor->user->middle_name, $name ) !== false) or
                (strpos( $doctor->user->last_name, $name ) !== false)){
                $details = array('doctor_id'=>$doctor->doctor_id,'first_name' =>$doctor->user->name,
                    'middle_name' => $doctor->user->middle_name,
                    'last_name' => $doctor->user->last_name,'title'=>$doctor->title,
                    'specialization_field_id'=>$doctor->specialization_field_id,'reg_number'=>$doctor->regNumber,
                    'mobile'=>$doctor->mobile);
                array_push($dataArray,$details);
            }
        }
        echo json_encode($dataArray);
    }

    public function registerPatient(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $user = new User();
        $user->name = $request->all()['firstName'];
        $user->middle_name=$request->all()['middleName'];
        $user->last_name=$request->all()['lastName'];
        $user->password = Hash::make($request->all()['password']);
        $user->email = $request->all()['email'];
        $user->role_id =2;
        $user->save();

        $email = $request->all()['email'];
        //before entering check whether email is unique!!!!
        $user_selected = User::all()->where('email',$email)->first();
        $userID = $user_selected->id;

        $patient = new patient();
        $patient->patient_id = $userID;
        $patient->title= $request->all()['title'];
        $patient->birthday= $request->all()['birthday'];
        $patient->gender= $request->all()['gender'];
        $patient->save();

        mkdir('../storage/app/records/patient_ID-'.$userID, 0777, true);

        $res =array('name'=>$request->all()['firstName'],
            'middle_name'=>$request->all()['middleName'],
            'last_name'=>$request->all()['lastName'],
            'email'=>$request->all()['email'],
            'password'=>$request->all()['password'],
            'role_id'=>2);
        echo json_encode($user_selected);

    }
    
    public function getReportsList($patient_id,$field){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $path = storage_path('app/records/patient_ID-'.$patient_id.'/'.$field);
        if(!file_exists($path)){
            echo json_encode("not found");
        }else{

            $files = File::allFiles($path); //set of objects of type splFileinfo. can use methods of that cls ie getPathName()

            $fileNames = array();
            $reports = array();
            foreach ($files as $f) {
                $file_name = $f->getFileName();
                array_push($fileNames, $f->getFileName());

                $file = \App\file::all()->where('patient_ID', $patient_id)
                    ->where('specialization_ID', $field)
                    ->where('file_name', $file_name)->first();

                if ($file != null) {
                    $notes = doctor_note::all()->where('file_ID', $file->file_id);
                    $doctor_notes = array();

                    if ($notes != null) {
                        foreach ($notes as $note) {
                            $doctor = doctor::with('user')->where('doctor_id', $note->doctor_ID)->get()->first();
                            $NOTE = array('note' => $note->note, 'doctor' => $doctor->user->name . ' ' . $doctor->user->last_name);
                            array_push($doctor_notes, $NOTE);
                        }
                    }
                    $fileDetails = array('fileID' => $file->file_id, 'fileName' => $file_name,
                        'description' => $file->description, 'notes' => $doctor_notes);

                    array_push($reports, $fileDetails);
                }
            }
            echo json_encode($reports);
        }

    }
    
    public function getReport($patient_id, $field_id,$file_name){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $path = storage_path('app/records/patient_ID-'.$patient_id.'/'.$field_id.'/'.$file_name);

        if(!file_exists($path)){
            echo json_encode("not found");
        }
        else
        {
            $file = file_get_contents($path);
            $type= mime_content_type($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return json_encode($response);
        }
    }
    public function getInitNotifications($patient_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $per = permission::all()->where('patient_ID',$patient_id)->where('status',0)->sortByDesc('updated_at');
        $permissions=array();
        foreach ($per as $p){

            $doctor = doctor::with('user')->where('doctor_id',$p['doctor_ID'])->get()->first();
            $field = specialization::all()->where('id',$p['field_ID'])->first();
            $doc_name = $doctor->user->name.' '.$doctor->user->middle_name.' '.$doctor->user->last_name;
            $temp= array('id'=>$p['id'],'doctor_ID'=>$p['doctor_ID'],'patient_ID'=>$p['patient_ID'],
                'field_ID'=>$p['field_ID'],'request'=>$p['request'], 'status'=>$p['status'],
                'doctor_name'=>$doc_name,'field'=>$field['field']);
            array_push($permissions,$temp);
        }
        return json_encode($permissions);
    }

    public function rejectPermission($permission_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $permission = new permission();
        $permission->exists = true;
        $permission->id = $permission_id;
        $permission->status = 2;
        if($permission->save()){
            $p = permission::all()->where('id',$permission_id)->first();
            $doctor_id  = $p->doctor_ID;
            $field_id = $p->field_ID;
            $doctor = doctor::with('user')->where('doctor_id',$doctor_id)->get()->first();
            $field = specialization::all()->where('id',$field_id)->first();
            $doc_name = $doctor->user->name.' '.$doctor->user->middle_name.' '.$doctor->user->last_name;
            $field_name = $field->field;
            $success = array('doctor'=>$doc_name,'field'=>$field_name);
            return json_encode($success);
        }else{
            return json_encode('error');
        }
        
    }
    public function acceptPermission($permission_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $permission = new permission();
        $permission->exists = true;
        $permission->id = $permission_id;
        $permission->status = 1;
        if($permission->save()){
            $p = permission::all()->where('id',$permission_id)->first();
            $doctor_id  = $p->doctor_ID;
            $field_id = $p->field_ID;
            $doctor = doctor::with('user')->where('doctor_id',$doctor_id)->get()->first();
            $field = specialization::all()->where('id',$field_id)->first();
            $doc_name = $doctor->user->name.' '.$doctor->user->middle_name.' '.$doctor->user->last_name;
            $field_name = $field->field;
            $success = array('doctor'=>$doc_name,'field'=>$field_name);
            return json_encode($success);
        }else{
            $error = array('error');
            return json_encode($error);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\allergy;
use App\bloodDonated;
use App\doctor;
use App\doctor_note;
use App\family;
use App\family_request;
use App\patient;
use App\medicineInfo;
use App\permission;
use App\specialization;
use App\User;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function getAllergies($id){
        header("Access-Control-Allow-Origin: *");
        $allergies = allergy::all()->where('patient_ID',$id)->all();
        //get allergies of the mobile app user
        $allegriesArray = array();
        if($allergies!=null){
            foreach ($allergies as $a) {
                array_push($allegriesArray,$a);
            }
            echo json_encode($allegriesArray);
        }
        else{
            echo json_encode('error');
        }

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
//            $allergies = allergy::all()->where('patient_ID',$request->only('userid'));
//            echo json_encode($allergies);
            $this->getAllergies($request->all()['userid']);
        }else{
            return response('error');
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
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
        $me_in_families= family::all()->where('family_id',$id);
        $data = array();
        if(sizeof($me_in_families)!=0){
            foreach ($me_in_families as $mem){
                $details = array('mem_first_name' =>$mem->patient->user->name,
                    'mem_middle_name' => $mem->patient->user->middle_name,
                    'mem_last_name' => $mem->patient->user->last_name,'mem_dob' => $mem->patient->birthday,
                    'mem_gender' => $mem->patient->gender,'mem_blood' => $mem->patient->blood_group,
                    'mem_id' => $mem->patient_ID,'mem_height' => $mem->patient->height,
                    'mem_weight' => $mem->patient->weight,
                    'mem_mobile' => $mem->patient->mobile);
                array_push($data,$details);
            }
        }
        if(sizeof($family_members)!=0){
            foreach ($family_members as $mem){
                $details = array('mem_first_name' =>$mem->family_member->user->name,'mem_middle_name' => $mem->family_member->user->middle_name,
                    'mem_last_name' => $mem->family_member->user->last_name,'mem_dob' => $mem->family_member->birthday,
                    'mem_gender' => $mem->family_member->gender,'mem_blood' => $mem->family_member->blood_group,
                    'mem_id' => $mem->family_id,'mem_height' => $mem->family_member->height,'mem_weight' => $mem->family_member->weight,
                    'mem_mobile' => $mem->family_member->mobile);
                array_push($data,$details);
            }
        }
        echo json_encode($data);
    }

    public function setFamilyMember($user_id,$member_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $fam_req = new family_request();
        $fam_req->patient_ID=$user_id;
        $fam_req->family_member_ID=$member_id;
        $fam_req->status = 0;

        $req1 = family_request::all()->where('patient_ID',$member_id)->
                where('family_member_ID',$user_id)->where('status',0)->first();
        if(sizeof($req1)==0){
            $req2 = family_request::all()->where('patient_ID',$user_id)->
                    where('family_member_ID',$member_id)->where('status',0)->first();
            if(sizeof($req2)==0){
                if($fam_req->save()) {
                    return json_encode("success");
                }
            }else{
                return json_encode("already requested");
            }
        }else{
            return json_encode("he/she requested");
        }
        
//        $fam = new family();
//        $fam->patient_ID = $user_id;
//        $fam->family_id = $member_id;
//        if($fam->save()){
//            $this->getFamilyMembers($user_id);
//        }
    }

    public function getFamilyMemRequests($user_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $requests = family_request::all()->where('family_member_ID',$user_id)->where('status',0);
        $data = array();
        if(sizeof($requests)!=0){
            foreach($requests as $req){
                $requested_at = $req->updated_at->diffForHumans();
                $temp = array('req_id'=>$req->id,'name'=>$req->patient->user->name,
                    'middle_name'=>$req->patient->user->middle_name,
                    'last_name'=>$req->patient->user->last_name,'patient_id'=>$req->patient_ID,
                    'title'=>$req->patient->title, 'birthday'=>$req->patient->birthday,
                    'gender'=>$req->patient->gender,'nationality'=>$req->patient->nationality,
                    'blood_group'=>$req->patient->blood_group,
                    'height'=>$req->patient->height,'weight'=>$req->patient->weight,
                    'hair_color'=>$req->patient->hair_color,
                    'eye_color'=>$req->patient->eye_color,'mobile'=>$req->patient->mobile,
                    'requested_at'=>$requested_at);
                array_push($data,$temp);
            }
            return json_encode($data);
        }
        return json_encode('none');
    }

    public function acceptFamilyMember($req_id,$user_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $req = new family_request();
        $req->exists=true;
        $req->id =$req_id;
        $req->status=1;
        if($req->save()){
            $request= family_request::all()->where('id',$req_id)->first();

            $fam_rec1 = family::all()->where('patient_ID',$request["patient_ID"])
                ->where('family_id',$request["family_member_ID"])->first();
            $fam_rec2 = family::all()->where('family_id',$request["patient_ID"])
                ->where('patient_ID',$request["family_member_ID"])->first();
            if(sizeof($fam_rec1)==0 && sizeof($fam_rec2)==0){
                $family = new family();
                $family->patient_ID = $request["patient_ID"];
                $family->family_id = $request["family_member_ID"];
                if($family->save()){
                    $requests = family_request::all()->where('family_member_ID',$user_id)->where('status',0);
                    $data = array();
                    if(sizeof($requests)!=0){
                        foreach($requests as $req){
                            $requested_at = $req->updated_at->diffForHumans();
                            $temp = array('req_id'=>$req->id,'name'=>$req->patient->user->name,
                                'middle_name'=>$req->patient->user->middle_name,
                                'last_name'=>$req->patient->user->last_name,'patient_id'=>$req->patient_ID,
                                'title'=>$req->patient->title, 'birthday'=>$req->patient->birthday,
                                'gender'=>$req->patient->gender,'nationality'=>$req->patient->nationality,
                                'blood_group'=>$req->patient->blood_group,
                                'height'=>$req->patient->height,'weight'=>$req->patient->weight,
                                'hair_color'=>$req->patient->hair_color,
                                'eye_color'=>$req->patient->eye_color,'mobile'=>$req->patient->mobile,
                                'requested_at'=>$requested_at);
                            array_push($data,$temp);
                        }
                        return json_encode($data);
                    }
                    return json_encode('none');
                }
                else{
                    return json_encode('none');
                }
            }else{
                return json_encode('none');
            }

        }else{
            return json_encode('none');
        }
    }
    
    public function discardFamilyMember($req_id,$user_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $req = new family_request();
        $req->exists=true;
        $req->id =$req_id;
        if($req->delete()){
            return json_encode('success');
         }else{
            return json_encode('error');
        }
        
    }

    public function deleteFamilyMember($user_id,$member_id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $family_member= family::all()->where('patient_ID',$user_id)->where('family_id',$member_id)->first();
        if(sizeof($family_member)==0){
            $me_in_fam = family::all()->where('family_id',$user_id)->where('patient_ID',$member_id)->first();
            if(sizeof($me_in_fam)==1) {
                $me_n_fam_rec = family::find($me_in_fam->id);
                if ($me_n_fam_rec->delete()) {
                    $this->getFamilyMembers($user_id);
                } else {
                    return json_encode("not authenticated");
                }
            }
        }else{
            $fam_record = family::find($family_member->id);
            if($fam_record->delete()) {
                $this->getFamilyMembers($user_id);
            }
            else{
                return json_encode("not authenticated");
            }
        }
    }
    public function mobileSearchPatients($user_id,$name){
        $family_members = patient::all();
        $dataArray = array();

        $data = array();
        $family_of_me= family::all()->where('patient_ID',$user_id);   //get all family members' (id)listed
        $me_in_families= family::all()->where('family_id',$user_id);

        if(sizeof($me_in_families)!=0){
            foreach ($me_in_families as $me){
                array_push($data,$me->patient_ID);
            }
        }
        if(sizeof($family_of_me)!=0){
            foreach ($family_of_me as $fam){
                array_push($data,$fam->family_id);
            }
        }
        array_push($data,$user_id);
        foreach ($family_members as $mem){
            $user = User::all()->where('id',$mem->patient_id)->first();
            if(!in_array($mem->patient_id,$data)){
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

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $doctors= doctor::all();
        $dataArray = array();
        foreach ($doctors as $doctor){
            if( (strpos( $doctor->user->name, $name ) !== false) or
                (strpos( $doctor->user->middle_name, $name ) !== false) or
                (strpos( $doctor->user->last_name, $name ) !== false)){
                $field = specialization::all()->where('id',$doctor->specialization_field_id)->first();
                $details = array('id'=>$doctor->doctor_id,'first_name' =>$doctor->user->name,
                    'middle_name' => $doctor->user->middle_name,
                    'last_name' => $doctor->user->last_name,'title'=>$doctor->title,
                    'field'=>$field->field,'reg_number'=>$doctor->regNumber,
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
            return $response;
//            return json_encode($response);
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
    
    public function patientUpload(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
//        $target_path = "uploads/";
        $path = storage_path('app/records/patient_ID-'.$request->all()['patient_id'].'/'.$request->all()['field_id'].'/'.$request->all()['fileName']);
//        $target_path = $target_path . basename( $_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            echo "Upload and move success";
        } else {
            echo $path;
            echo "There was an error uploading the file, please try again!";
        }
    }

    public function getLastBloodDonated($id){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $donated = bloodDonated::all()->where('patient_ID',$id)->first();
        if($donated!=null){
            return json_encode($donated->donated);
        }
        else{
            return json_encode('error');
        }
    }
    public function setBloodDonated(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        $donated = new bloodDonated();
        $existRecord = bloodDonated::all()->where('patient_ID',$request->all()['userid'])->first();
        if($existRecord!=null){
            $donated->id = $existRecord->id;
            $donated->exists = true;
            $donated->donated = $request->all()['donated'];
            if($existRecord->donated == $request->all()['donated'] ){
                return json_encode($existRecord->donated);
            }else{
                if($donated->save()){
                    $updated = bloodDonated::all()->where('patient_ID',$request->all()['userid'])->first();
                    return json_encode($updated->donated);
                }else{
                    return json_encode('error');
                }
            }
        }
        else{
            $donated->patient_ID = $request->all()['userid'];
            $donated->donated = $request->all()['donated'];
            if($donated->save()){
                $updated = bloodDonated::all()->where('patient_ID',$request->all()['userid'])->first();
                return json_encode($updated->donated);
            }else{
                return json_encode('error');
            }
        }

    }
    public function getActiveDoctors($patient_id){
        $permissions = permission::all()->where('patient_ID',$patient_id)->where('status',1);
        $active = array();
            if(sizeof($permissions)!=0){
           foreach ($permissions as $p){
                $doctorID = $p->doctor_ID;
                $doctor = doctor::with('user')->where('doctor_id',$doctorID)->get()->first();
                $field = specialization::all()->where('id',$p->field_ID)->first();
                $updated = $p->updated_at->diffForHumans();
//            $now =date("Y-m-d H:i:s");
////            $timeArray = explode(' ',$updated);
////            echo var_dump($updated);
////       echo \DateTime::createFromFormat("Y-m-d H:i:s",$permissions->updated_at)->diff(new \DateTime('now'));
////            $timeRemaining = $now-$updated;
////            echo $timeRemaining;
                $temp =array('permission_id'=>$p->id,'doc_id'=>$doctor->doctor_id,
                    'first_name' =>$doctor->user->name,
                    'middle_name' => $doctor->user->middle_name,
                    'last_name' => $doctor->user->last_name,'title'=>$doctor->title,
                    'field'=>$field->field,'reg_number'=>$doctor->regNumber,
                    'mobile'=>$doctor->mobile,'allowedTime'=>$updated);
                array_push($active,$temp);
            }
            return json_encode($active);
        }
       return json_encode('error');
    }
    public function discardPermission($permission_id,$patient_id){
        $permission = new permission();
        $permission->exists=true;
        $permission->id =$permission_id;
        $permission->status = 2; //discarding
        if($permission->save()){
            $permissions = permission::all()->where('patient_ID',$patient_id)->where('status',1);
            $active = array();
            if(sizeof($permissions)!=0){
                foreach ($permissions as $p){
                    $doctorID = $p->doctor_ID;
                    $doctor = doctor::with('user')->where('doctor_id',$doctorID)->get()->first();
                    $field = specialization::all()->where('id',$p->field_ID)->first();
                    $updated = $p->updated_at->diffForHumans();
                    $temp =array('permission_id'=>$p->id,'doc_id'=>$doctor->doctor_id,
                        'first_name' =>$doctor->user->name,
                        'middle_name' => $doctor->user->middle_name,
                        'last_name' => $doctor->user->last_name,'title'=>$doctor->title,
                        'field'=>$field->field,'reg_number'=>$doctor->regNumber,
                        'mobile'=>$doctor->mobile,'allowedTime'=>$updated);
                    array_push($active,$temp);
                }
                return json_encode($active);
            }else{
                return json_encode('none has permission');
            }
        }
        return json_encode('error');
    }
}

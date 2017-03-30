<?php

namespace App\Http\Controllers;

use App\allergy;
use App\family;
use App\patient;
use App\medicineInfo;
use App\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function getAllergies($id){
        header("Access-Control-Allow-Origin: *");
        $allergies = allergy::all()->where('patient_ID',$id);
        echo json_encode($allergies);
    }

    public function setAllergy (Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $allergy= new allergy();
        $allergy->patient_ID = $request->all()['userid'];
        $allergy->medicine = $request->all()['medicine'];
        $allergy->allergy = $request->all()['allergy'];

        if($allergy->save()){
            $allergies = allergy::all()->where('patient_ID',$request->only('userid'));
            echo json_encode($allergies);
        }else{
            return response('not authenticated', 404);
        }
    }
    public function getMedicineInfo($id){
        header("Access-Control-Allow-Origin: *");
        $medicineInfo = medicineInfo::all()->where('patient_ID',$id);
        echo json_encode($medicineInfo);
    }

    public function setMedicineInfo(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $medicineInfo= new medicineInfo();
        $medicineInfo->patient_ID = $request->all()['userid'];
        $medicineInfo->medicine = $request->all()['medicine'];
        $medicineInfo->info = $request->all()['info'];

        if($medicineInfo->save()){
            $medicineInfo = medicineInfo::all()->where('patient_ID',$request->only('userid'));
            echo json_encode($medicineInfo);
        }else{
            return response('not authenticated', 404);
        }
    }

    public function getFamilyMembers($id){
        header("Access-Control-Allow-Origin: *");
        $family_members= family::all()->where('patient_ID',$id);
        //access user information of family members
        //        echo dd($family_members[0]->family_member->user->name);
        $data = array();

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

    public function setFamilyMember(Request $request){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        $member= new family();
        $member->patient_ID = $request->all()['userid'];
        $member->family_id = $request->all()['member_id'];

        if($member->save()){
            $family_members = family::all()->where('patient_ID',$request->only('userid'));
            $data = array();
            foreach ($family_members as $mem){
                $details = array('mem_first_name' =>$mem->family_member->user->name,'mem_middle_name' => $mem->family_member->user->middle_name,
                    'mem_last_name' => $mem->family_member->user->last_name,'mem_dob' => $mem->family_member->birthday,
                    'mem_gender' => $mem->family_member->gender,'mem_blood' => $mem->family_member->blood_group,
                    'mem_id' => $mem->family_id,'mem_height' => $mem->family_member->height,'mem_weight' => $mem->family_member->weight,
                    'mem_mobile' => $mem->family_member->mobile);
                array_push($data,$details);
            }
            echo json_encode($data);
        }else{
            return response('not authenticated', 404);
        }
    }

    public function mobileSearchPatients($name){
//        $family_members= family::all()->where('name','like', '%' . $name . '%');
//        $family_members= User::all()->where('role_id',2)->where('name','LIKE',"%{$name}%");
        $family_members= User::all()->where('role_id',2);

        $dataArray = array();
        foreach ($family_members as $mem){
            if( (strpos( $mem->name, $name ) !== false) or
                (strpos( $mem->middle_name, $name ) !== false) or
                (strpos( $mem->last_name, $name ) !== false)){
                $details = array('mem_id'=>$mem->id,'mem_first_name' =>$mem->name,'mem_middle_name' => $mem->middle_name,
                    'mem_last_name' => $mem->last_name);
                array_push($dataArray,$details);
            }
        }
        echo json_encode($dataArray);
    }
}

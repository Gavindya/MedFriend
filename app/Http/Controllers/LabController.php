<?php

namespace App\Http\Controllers;

use App\file;
use App\specialization;
use App\User;
use App\patient;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabController extends Controller
{
    public function searchPatients(Request $request){
//       still working on getting exact search result
        
        $keyword = $request->all()['$value'];  //ajax request sent with parameter $value which contains the string to be searched
        if ($request->ajax()){
            $output = "";
//            $patients = DB::table('users')->where('role_id','=',2)->where('name','LIKE','%'.$keyword.'%')
//                ->orWhere('middle_name','LIKE','%'.$keyword.'%')
//                ->orWhere('last_name','LIKE','%'.$keyword.'%')->get();
            $patients = User::all()->where('role_id',2);
//            $patients =User::all()->where('name','LIKE',$keyword.'%');
            if($patients)
            {

                foreach ($patients as $patient)
                {
//                  output following html code under searchResults
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
        $fields = specialization::all();
//        echo dd($fields);
        return view('labAssistant.uploadFilePage',['patientID'=>$id,'fields'=>$fields]);
    }

    public function uploadFile(Request $request){
        //NOT PROPERLY IMPLEMENTED.
        
        $this->validate($request, [
            'doc' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);
        //***************************STORE IN PUBLIC/UPLOADS FOLDER**************
        //should get id of the entered member****IMPORTANT!!!!!
        $category = $request->all()['field_id'];
//        echo dd($category);

        $dateTime = (new \DateTime())->format('Y-m-d H-i-s');
        $fileName =$dateTime.'.'.$request->file('doc')->getClientOriginalExtension();
        $request->file('doc')->storePubliclyAs('records/patient_ID-'.$request->all()['patient_id'].'/'.$category, $fileName);
        
        $file = new file();
        $file->patient_ID=$request->all()['patient_id'];
        $file->specialization_ID=$category;
        $file->file_name=$fileName;
        $file->description='';
        $file->extension=$request->file('doc')->getClientOriginalExtension();
        $file->save();
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

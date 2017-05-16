<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

//Lab Assistants routes

Route::get('/labHome', [
    'uses' => 'LabController@labHomePage',
    'as'=>'labHome'
]);

Route::get('/searchPatients', [
    'uses' => 'LabController@searchPatients',
    'as'=>'searchPatients'
]);

Route::get('/uploadFilePage/{id}', [
    'uses' => 'LabController@uploadFilePage',
    'as' => 'uploadFilePage'
]);

Route::patch('/uploadFile', [
    'uses' => 'LabController@uploadFile',
    'as' => 'uploadFile'
]);

//doctors

Route::get('/doc_searchPatients', [
    'uses' => 'DoctorController@doc_searchPatients',
    'as'=>'doc_searchPatients'
]);

Route::get('/doctorHome', [
    'uses' => 'DoctorController@doctorHome',
    'as' => 'doctorHome'
]);

Route::get('/getSelectedPatient', [
    'uses' => 'DoctorController@getSelectedPatient',
    'as'=>'getSelectedPatient'
]);

Route::get('/viewPatient/{id}', [
    'uses' => 'DoctorController@viewPatient',
    'as'=>'viewPatient'
]);

Route::patch('/viewReport', [
    'uses' => 'DoctorController@viewReport',
    'as'=>'viewReport'
]);

Route::get('/view/{user}/{field}/{file}', [
    'uses' => 'DoctorController@view',
    'as'=>'view'
]);

Route::get('/listOutRecords/{patient_id}/{specialization_field_id}', [
    'uses' => 'DoctorController@listOutRecords',
    'as'=>'listOutRecords'
]);

Route::get('/askPermissionPage/{doctor_id}', [
    'uses' => 'DoctorController@askPermissionPage',
    'as'=>'askPermissionPage'
]);

Route::get('/askPermission/{doctor_id}/{patient_id}/{requesting_field_id}', [
    'uses' => 'DoctorController@askPermission',
    'as'=>'askPermission'
]);

Route::patch('/addNotes', [
    'uses' => 'DoctorController@addNotes',
    'as'=>'addNotes'
]);

//-----------TEST ANG2----------
Route::get('/test', [
    'uses' => 'DoctorController@test',
    'as'=>'test'
]);

Route::post('/getLoginData', [
    'uses' => 'Auth\LoginController@getLoginData',
    'as'=>'getLoginData'
]);

Route::get('/getAllergies/{id}', [
    'uses' => 'PatientController@getAllergies',
    'as'=>'getAllergies'
]);

Route::post('/setAllergy', [
    'uses' => 'PatientController@setAllergy',
    'as'=>'setAllergy'
]);

Route::get('/getMedicineInfo/{id}', [
    'uses' => 'PatientController@getMedicineInfo',
    'as'=>'getMedicineInfo'
]);

Route::post('/setMedicineInfo', [
    'uses' => 'PatientController@setMedicineInfo',
    'as'=>'setMedicineInfo'
]);

Route::get('/getFamilyMembers/{id}', [
    'uses' => 'PatientController@getFamilyMembers',
    'as'=>'getFamilyMembers'
]);

Route::get('/setFamilyMember/{user_id}/{member_id}', [
    'uses' => 'PatientController@setFamilyMember',
    'as'=>'setFamilyMember'
]);

Route::get('/getFamilyMemRequests/{user_id}', [
    'uses' => 'PatientController@getFamilyMemRequests',
    'as'=>'getFamilyMemRequests'
]);

Route::get('/acceptFamilyMember/{req_id}/{user_id}', [
    'uses' => 'PatientController@acceptFamilyMember',
    'as'=>'acceptFamilyMember'
]);

Route::get('/discardFamilyMember/{req_id}/{user_id}', [
    'uses' => 'PatientController@discardFamilyMember',
    'as'=>'discardFamilyMember'
]);


Route::get('/deleteFamilyMember/{user_id}/{member_id}', [
    'uses' => 'PatientController@deleteFamilyMember',
    'as'=>'deleteFamilyMember'
]);

Route::get('/mobileSearchPatients/{user_id}/{name}', [
    'uses' => 'PatientController@mobileSearchPatients',
    'as'=>'mobileSearchPatients'
]);

Route::get('/getProfileDetails/{id}', [
    'uses' => 'PatientController@getProfileDetails',
    'as'=>'getProfileDetails'
]);

Route::post('/setProfileDetails', [
    'uses' => 'PatientController@setProfileDetails',
    'as'=>'setProfileDetails'
]);

Route::get('/mobileSearchDoctors/{name}', [
    'uses' => 'PatientController@mobileSearchDoctors',
    'as'=>'mobileSearchDoctors'
]);

Route::get('/getActiveDoctors/{patient_id}', [
    'uses' => 'PatientController@getActiveDoctors',
    'as'=>'getActiveDoctors'
]);

Route::post('/registerPatient', [
    'uses' => 'PatientController@registerPatient',
    'as'=>'registerPatient'
]);

Route::get('/getReportsList/{id}/{field}', [
    'uses' => 'PatientController@getReportsList',
    'as'=>'getReportsList'
]);

Route::get('/getReport/{patient_id}/{field_id}/{file_name}', [
    'uses' => 'PatientController@getReport',
    'as'=>'getReport'
]);

Route::get('/getInitNotifications/{patient_id}', [
    'uses' => 'PatientController@getInitNotifications',
    'as'=>'getInitNotifications'
]);

Route::get('/rejectPermission/{permission_id}', [
    'uses' => 'PatientController@rejectPermission',
    'as'=>'rejectPermission'
]);

Route::get('/acceptPermission/{permission_id}', [
    'uses' => 'PatientController@acceptPermission',
    'as'=>'acceptPermission'
]);

Route::get('/getLastBloodDonated/{id}', [
    'uses' => 'PatientController@getLastBloodDonated',
    'as'=>'getLastBloodDonated'
]);

Route::post('/setBloodDonated', [
    'uses' => 'PatientController@setBloodDonated',
    'as'=>'setBloodDonated'
]);

Route::post('/patientUpload', [
    'uses' => 'PatientController@patientUpload',
    'as'=>'patientUpload'
]);

Route::get('/discardPermission/{permission_id}/{patient_id}', [
    'uses' => 'PatientController@discardPermission',
    'as'=>'discardPermission'
]);


//------------------

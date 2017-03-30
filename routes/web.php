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
Route::get('/doctorHome', [
    'uses' => 'DoctorController@doctorHome',
    'as' => 'doctorHome'
]);

Route::get('/getSelectedPatient', [
    'uses' => 'DoctorController@getSelectedPatient',
    'as'=>'getSelectedPatient'
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

Route::post('/setFamilyMember', [
    'uses' => 'PatientController@setFamilyMember',
    'as'=>'setFamilyMember'
]);

Route::get('/mobileSearchPatients/{name}', [
    'uses' => 'PatientController@mobileSearchPatients',
    'as'=>'mobileSearchPatients'
]);


//------------------
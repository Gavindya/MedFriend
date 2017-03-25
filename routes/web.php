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

Route::get('/doctorHome', [
    'uses' => 'DoctorController@doctorHome',
    'as' => 'doctorHome'
]);

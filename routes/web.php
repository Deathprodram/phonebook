<?php

use Illuminate\Support\Facades\Route;

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
    return view('layouts.admin');
});

Route::get('/country/list', ['\App\Http\Controllers\CountryController', 'getCountryList']);

Route::get('/phonebook/list', ['\App\Http\Controllers\PhoneController', 'getPhoneList']);
Route::post('/phonebook/create', ['\App\Http\Controllers\PhoneController', 'createPhoneData']);
Route::post('/phonebook/edit', ['\App\Http\Controllers\PhoneController', 'editPhoneData']);
Route::delete('/phonebook/destroy', ['\App\Http\Controllers\PhoneController', 'destroyPhoneData']);

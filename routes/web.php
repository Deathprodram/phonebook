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
    $phone = \App\Models\Phone::find(4);
//    dd($phone->user->name);
    $phone->user->name = 'Samat';
    $phone->phone = '7475300039';
    $phone->user->save();
    $phone->save();
//    dd(\App\Models\Phone::first()->phone);
//    $countries = file_get_contents('http://country.io/names.json');
//    $countries = json_decode($countries, true);
//
//    $codes = file_get_contents('http://country.io/phone.json');
//    $codes = json_decode($codes, true);
//    // dd(array_merge_recursive($countries, $codes));
//    $d = [];
//    foreach (array_merge_recursive($countries, $codes) as $k => $v) {
////        \App\Models\Country::create([
////            'name' => $v[0],
////            'phone_code' => $v[1],
////            'shortcode' => $k,
////        ]);
//    }
//    dd($d);
    return view('layouts.admin');
});

Route::get('/country/list', ['\App\Http\Controllers\CountryController', 'getCountryList']);

Route::get('/phonebook/list', ['\App\Http\Controllers\PhoneController', 'getPhoneList']);
Route::post('/phonebook/create', ['\App\Http\Controllers\PhoneController', 'createPhoneData']);
Route::post('/phonebook/edit', ['\App\Http\Controllers\PhoneController', 'editPhoneData']);
Route::delete('/phonebook/destroy', ['\App\Http\Controllers\PhoneController', 'destroyPhoneData']);

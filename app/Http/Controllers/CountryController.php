<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getCountryList() {
        return Country::orderBy('shortcode', 'asc')->get();
    }
}

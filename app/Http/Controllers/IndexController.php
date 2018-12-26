<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\M_facility_type;

class IndexController extends Controller
{
    public function index()
    {
        $facility_types = M_facility_type::all();

        return view("html.index3", ['facility_types' => $facility_types]);
    }
}

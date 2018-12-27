<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\M_facility_type;
use App\Facility;
use App\M_answer_cd;

class IndexController extends Controller
{
    public function index()
    {
        $facility_types = M_facility_type::all();

        $city_names = Facility::select('city_name')->distinct()->get();

        $acceptable_patients = M_answer_cd::where('answer_group_cd', 'AA01')->get();

        $acceptable_cities = M_answer_cd::where('answer_group_cd', 'AA02')->get();

        //dd($city_names);

        return view("html.index3",
            [
                'facility_types' => $facility_types,
                'city_names' => $city_names,
                'acceptable_cities' => $acceptable_cities,
                'acceptable_patients' => $acceptable_patients
            ]
        );
    }
}

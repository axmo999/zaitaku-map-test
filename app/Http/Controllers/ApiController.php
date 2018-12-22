<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\M_question_cd;

class ApiController extends Controller
{
    public function search(Request $request)
    {
        $datas = $request->all();
        $queryFacility = Facility::query();

        if($datas)
        {
            if (array_key_exists('city_name', $datas))
            {
                $queryFacility->WhereIn('city_name', explode(",", $datas["city_name"]));
            }

            if (array_key_exists('address', $datas))
            {
                $queryFacility->Where('address', 'like', "%".$datas["address"]."%");
            }

            if (array_key_exists('facility_type_id', $datas))
            {
                $queryFacility->WhereIn('facility_type_id', explode(",", $datas["facility_type_id"]));
            }
        }

        return response()->json($queryFacility->get());
    }
}

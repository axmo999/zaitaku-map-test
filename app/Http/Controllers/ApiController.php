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

        //\Log::info(array_values($datas["facility_type_id"]));

        $queryFacility = Facility::query();
        $queryFacility->with("facilityType:id,facility_type_name");
        $queryFacility->select('id', 'facility_name', 'facility_type_id', 'city_name', 'address', 'telphone', 'latitude', 'longitude');

        if ($datas) {
            if (array_key_exists('city_name', $datas)) {
                $queryFacility->WhereIn('city_name', explode(",", $datas["city_name"]));
            }

            if (array_key_exists('address', $datas)) {
                $queryFacility->Where('address', 'like', "%".$datas["address"]."%");
            }

            if (array_key_exists('facility_type_id', $datas)) {
                $queryFacility->WhereIn('facility_type_id', array_values($datas["facility_type_id"]));
            }
        }

        //\Log::info($queryFacility->get());

        //dd($queryFacility->get());
        return response()->json($queryFacility->get());
    }
}

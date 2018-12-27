<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\M_question_cd;
use App\Answer;

class ApiController extends Controller
{
    public function search(Request $request)
    {
        $datas = $request->all();

        $answerFacilities = array();

        \Log::info($datas);

        $queryFacility = Facility::query();
        $queryFacility->with("facilityType:id,facility_type_name");
        $queryFacility->select('id', 'facility_name', 'facility_type_id', 'city_name', 'address', 'telphone', 'latitude', 'longitude');

        //$queryAnswer = Answer::query();

        if ($datas) {
            if (array_key_exists('city_names', $datas)) {
                $queryFacility->WhereIn('city_name', array_values($datas["city_names"]));
            }

            if (array_key_exists('address', $datas)) {
                $queryFacility->Where('address', 'like', "%".$datas["address"]."%");
            }

            if (array_key_exists('facility_type_id', $datas)) {
                $queryFacility->WhereIn('facility_type_id', array_values($datas["facility_type_id"]));
            }

            if (array_key_exists('home_care', $datas)) {
                $queryFacility->Where('home_care', array_values($datas["home_care"]));
            }

            if (array_key_exists('acceptable_cities', $datas)) {
                $answerFacilities = $this->getFacilityId($datas, "acceptable_cities", $answerFacilities);
                //$queryFacility->WhereIn('id', array_values($answerFacilities));
                \Log::info($answerFacilities);
            }

            if (array_key_exists('acceptable_patients', $datas)) {
                $answerFacilities = $this->getFacilityId($datas, "acceptable_patients", $answerFacilities);
                //$queryFacility->WhereIn('id', array_values($answerFacilities));
                \Log::info($answerFacilities);
            }
        }

        if($answerFacilities){
            $queryFacility->WhereIn('id', array_values($answerFacilities));
        }

        //\Log::info($queryAnswer->get());

        //dd($queryFacility->get());
        return response()->json($queryFacility->get());
    }

    public function getFacilityId($datas, $attributeName, $filterId)
    {
        $queryAnswer = Answer::query();
        $queryAnswer->Where('answer_cd', array_values($datas[$attributeName]));
        $answerFacilities = $queryAnswer->pluck('facility_id')->all();
        if($filterId){
            $answerFacilities = array_intersect($answerFacilities, $filterId );
        }
        return $answerFacilities;
    }

}

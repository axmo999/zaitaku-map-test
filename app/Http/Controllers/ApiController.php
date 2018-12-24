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
                $queryFacility->WhereIn('facility_type_id', explode(",", $datas["facility_type_id"]));
            }
        }

        // $facilities = $queryFacility->get();

        // $returnDatas = [];

        // foreach ($facilities as $facility) {
        //     $returnDatas = [
        //         $facility->id,
        //         $facility->facility_name,
        //         $facility->facility_name,
        //         $facility->city_name + $facility->address,
        //         $facility->
        //         $facility->
        //         $facility->
        //     ];
        // }



        return response()->json($queryFacility->get());
    }
}

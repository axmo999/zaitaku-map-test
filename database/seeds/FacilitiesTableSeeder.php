<?php

use Illuminate\Database\Seeder;

class FacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new SplFileObject('database/csv/facilities.csv');
        $file->setFlags(
            \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
        );
        $list = [];
        $row_count = 1;
        foreach ($file as $line) {
            if ($row_count > 1) {

                $list[] = [
                    "facility_name" => $line[0],
                    "home_care" => $line[1],
                    "facility_type_id" => $line[2],
                    "postal_code" => $line[3],
                    "prefecture_name" => $line[4],
                    "city_name" => $line[5],
                    "address" => $line[6],
                    "latitude" => $line[7],
                    "longitude" => $line[8],
                    "telphone" => $line[9],
                    "fax" => $line[10],
                    "representative" => $line[11],
                    "homepage" => $line[12],
                    "available_time_mon" => $line[13],
                    "available_time_tue" => $line[14],
                    "available_time_wed" => $line[15],
                    "available_time_thu" => $line[16],
                    "available_time_fri" => $line[17],
                    "available_time_sat" => $line[18],
                    "available_time_sun" => $line[19],
                    "person" => $line[20],
                    "correspondence_dept" => $line[21],
                    "correspondence_time" => $line[22],
                    "open_24hours" => $line[23],
                    "foreign_language" => $line[24],
                    "related_facilities" => $line[25],
                    "options" => $line[26],
                    "note" => $line[27],
                    "publish" => $line[28],
                    "user_id" => $line[29],
                    "created_at" => new DateTime($line[30]),
                    "updated_at" => new DateTime($line[31]),
                ];

            }
            $row_count++;
        }

        foreach (array_chunk($list, 1000) as $part) {
            DB::table("facilities")->insert($part);
        }

    }
}

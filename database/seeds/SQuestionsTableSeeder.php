<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new SplFileObject('database/csv/s_questions.csv');
        $file->setFlags(
            \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
        );
        $list = [];
        $now = Carbon::now();
        $row_count = 1;
        foreach ($file as $line) {
            if ($row_count > 1) {
                $list[] = [
                    "facility_type_id" => $line[0],
                    "question_cd" => $line[1],
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
            }
            $row_count++;
        }

        DB::table("s_questions")->insert($list);
    }
}

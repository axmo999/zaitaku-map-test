<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MQuestionCdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new SplFileObject('database/csv/m_question_cds.csv');
        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );
        $list = [];
        $now = Carbon::now();
        $row_count = 1;
        foreach($file as $line){
            if ($row_count > 1) {
                $list[] = [
                    "question_cd" => $line[0],
                    "question_content" => $line[1],
                    "question_type" => $line[2],
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
            }
            $row_count++;
        }

        DB::table("m_question_cds")->insert($list);
    }
}

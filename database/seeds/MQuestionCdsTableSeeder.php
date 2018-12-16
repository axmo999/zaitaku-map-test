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
/*         $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        ); */
        $list = [];
        $now = Carbon::now();
        foreach($file as $line){
            $new_line = str_getcsv($line);
            echo $new_line[0];
            echo $new_line[1];
            $list[] = [
                "question_cd" => $new_line[0],
                "question_content" => $new_line[1],
                "question_type" => $new_line[2],
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }

        DB::table("m_question_cds")->DB::insert($list);
    }
}

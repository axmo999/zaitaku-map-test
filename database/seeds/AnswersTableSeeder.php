<?php

use Illuminate\Database\Seeder;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new SplFileObject('database/csv/answers.csv');
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
                    "facility_id" => $line[0],
                    "question_cd" => $line[1],
                    "answer_cd" => $line[2],
                    "answer_conent" => $line[3],
                    "created_at" => new DateTime($line[4]),
                    "updated_at" => new DateTime($line[5])
                ];
            }
            $row_count++;
        }

        foreach(array_chunk($list, 1000) as $part){
            DB::table("answers")->insert($part);
        }


    }
}

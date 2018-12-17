<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(FacilitiesTableSeeder::class);
        $this->call(MFaciliryTypesTableSeeder::class);
        $this->call(MAnswerCdsTableSeeder::class);
        $this->call(MQuestionCdsTableSeeder::class);
        $this->call(SQuestionsTableSeeder::class);
    }
}

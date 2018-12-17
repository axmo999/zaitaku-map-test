<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_questions', function (Blueprint $table) {
            $table->increments('id')->comment("設問分類ID");
            $table->integer('facility_type_id')->comment("施設分類ID");
            $table->string('question_cd')->comment("設問コード");
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE s_questions COMMENT '設問分類テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_questions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMQuestionCdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_question_cds', function (Blueprint $table) {
            $table->increments('id')->comment("設問マスタID");
            $table->string('question_cd')->comment("設問コード");
            $table->string('question_content')->comment("設問内容");
            $table->string('question_type')->comment("設問タイプ");
            $table->string('answer_group_cd')->comment("回答グループコード");
            $table->integer('question_priority')->unsigned()->comment("設問優先度");
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_question_cds COMMENT '設問マスターテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_question_cds');
    }
}

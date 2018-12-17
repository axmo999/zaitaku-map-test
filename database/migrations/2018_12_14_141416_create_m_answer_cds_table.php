<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMAnswerCdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_answer_cds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('answer_group_cd')->comment("回答グループコード");
            $table->string('answer_cd')->comment("回答コード");
            $table->string('answer_content')->comment("回答内容");
            $table->timestamps();
        });

         // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_answer_cds COMMENT '回答マスターテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_answer_cds');
    }
}

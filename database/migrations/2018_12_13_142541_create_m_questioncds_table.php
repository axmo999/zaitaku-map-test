<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMQuestioncdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_questioncds', function (Blueprint $table) {
            $table->increments('id')->comment("設問マスタID");
            $table->string('question_cd')->comment("設問コード");
            $table->string('question_content')->comment("設問内容");
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_questioncds COMMENT '設問マスターテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_questioncds');
    }
}

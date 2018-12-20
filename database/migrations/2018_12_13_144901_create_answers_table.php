<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id')->comment("回答ID");
            $table->integer('facility_id')->comment("施設ID");
            $table->string('question_cd')->comment("設問コード");
            $table->string('answer_cd')->comment("回答コード");
            $table->string('answer_content')->comment("回答内容");
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE answers COMMENT '回答テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}

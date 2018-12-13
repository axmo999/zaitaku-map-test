<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_classes', function (Blueprint $table) {
            $table->increments('id')->comment("設問分類ID");
            $table->integer('category_id')->comment("カテゴリーID");
            $table->string('question_cd')->comment("設問コード");
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE question_classes COMMENT '設問分類テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_classes');
    }
}

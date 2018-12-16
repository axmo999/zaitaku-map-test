<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_categories', function (Blueprint $table) {
            $table->increments('id')->comment("カテゴリーID");
            $table->string('category_name')->comment("カテゴリー名");
            $table->timestamps();
        });

         // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_categories COMMENT 'カテゴリーマスターテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_categories');
    }


}

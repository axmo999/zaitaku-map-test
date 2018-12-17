<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMFacilityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_facility_types', function (Blueprint $table) {
            $table->increments('id')->comment("施設分類ID");
            $table->string('facility_type_name')->comment("施設分類名");
            $table->timestamps();
        });

         // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_facility_types COMMENT '施設分類マスターテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_facility_types');
    }


}

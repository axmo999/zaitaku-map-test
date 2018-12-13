<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->increments('id')->comment("施設ID");
            $table->string('facility_name')->comment("施設名");
            $table->integer('category_id')->unsigned()->comment("カテゴリーID");
            $table->string('postal_code')->comment("郵便番号")->nullable();
            $table->string('prefecture_name')->comment("都道府県名")->nullable();
            $table->string('city_name')->comment("市町村名")->nullable();
            $table->string('address')->comment("住所")->nullable();
            $table->double('latitude', 9, 6)->comment("緯度")->nullable();
            $table->double('longitude', 9, 6)->comment("経度")->nullable();
            $table->string('telphone')->comment("電話番号")->nullable();
            $table->string('fax')->comment("FAX番号")->nullable();
            $table->string('representative')->comment("代表者名")->nullable();
            $table->string('homepage')->comment("ホームページ")->nullable();
            $table->timestamps();
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE facilities COMMENT '施設基本情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}

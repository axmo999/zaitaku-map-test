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
            $table->boolean('home_care')->comment("在宅対応可")->nullable();
            $table->integer('facility_type_id')->unsigned()->comment("施設分類ID");
            $table->string('postal_code')->comment("郵便番号")->nullable();
            $table->string('prefecture_name')->comment("都道府県名")->nullable();
            $table->string('city_name')->comment("市町村名")->nullable();
            $table->string('address')->comment("住所")->nullable();
            $table->double('latitude', 9, 6)->comment("緯度")->nullable();
            $table->double('longitude', 9, 6)->comment("経度")->nullable();
            $table->string('telphone')->comment("電話番号")->nullable();
            $table->string('fax')->comment("FAX番号")->nullable();
            $table->string('representative')->comment("代表者名")->nullable();
            $table->string('homepage', '2083')->comment("ホームページ")->nullable();
            $table->string('available_time_mon')->comment("診療・営業時間（月曜日）")->nullable();
            $table->string('available_time_tue')->comment("診療・営業時間（火曜日）")->nullable();
            $table->string('available_time_wed')->comment("診療・営業時間（水曜日）")->nullable();
            $table->string('available_time_thu')->comment("診療・営業時間（木曜日）")->nullable();
            $table->string('available_time_fri')->comment("診療・営業時間（金曜日）")->nullable();
            $table->string('available_time_sat')->comment("診療・営業時間（土曜日）")->nullable();
            $table->string('available_time_sun')->comment("診療・営業時間（日・祝日）")->nullable();
            $table->string('person')->comment("窓口担当者")->nullable();
            $table->string('correspondence_dept')->comment("窓口対応部署")->nullable();
            $table->string('correspondence_time')->comment("窓口対応時間")->nullable();
            $table->boolean('open_24hours')->comment("24時間対応")->nullable();
            $table->boolean('foreign_language')->comment("外国語対応")->nullable();
            $table->text('related_facilities')->comment("併設・関連施設")->nullable();
            $table->text('options')->comment("オプション・事業所のアピール等")->nullable();
            $table->text('note')->comment("特記")->nullable();
            $table->boolean('publish')->comment("公開フラグ")->nullable();
            $table->integer('user_id')->unsigned()->comment("管理者ID");
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

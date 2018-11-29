<?php

use Illuminate\Database\Seeder;

class FacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $geometry = 'POINT(139.762522 35.706752)';

        DB::table('facilities')->insert([
            'facility_name' => 'テスト施設',
            'postal_code' => '905-1204',
            'prefecture_name' => '沖縄県',
            'city_name' => '東村',
            'address' => '平良831-2',
            'latitude' => '35.706752',
            'longitude' => '139.762522',
            'telphone' => '0980-43-3800',
            'fax' => '0980-43-3801',
            'representative' => '山城豊',
            'homepage' => '',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

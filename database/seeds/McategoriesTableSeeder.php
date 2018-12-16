<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_categories')->insert([
            [
                'category_name' => '保健所等',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '保険調剤薬局（在宅）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '入所施設',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '地域包括支援センター',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '小規模多機能型居宅介護',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '居宅介護支援事業所',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '歯科',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '病院',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '病院（在宅）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '在宅療養後方支援病院',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '福祉用具貸与',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '訪問リハビリテーション',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '訪問介護事業所（ヘルパーステーション）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '訪問入浴',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '訪問歯科',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '訪問看護ステーション',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '診療所',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '診療所（在宅）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '認知症対応型通所介護',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '調剤薬局',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '通所リハビリテーション（デイケア）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '通所介護（デイサービス）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'category_name' => '重度認知症患者デイケア（医療保険）',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);
    }
}

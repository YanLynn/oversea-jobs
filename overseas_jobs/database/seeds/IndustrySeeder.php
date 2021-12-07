<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['industry_name' => 'ファッション・アパレル・アクセサリー関連'],
            ['industry_name' => '消費財/FMCGメーカー'],
            ['industry_name' => '製薬メーカー'],
            ['industry_name' => '半導体・電気・電子部品メーカー'],
            ['industry_name' => '自動車・輸送機器メーカー'],
            ['industry_name' => '銀行・証券関連'],
            ['industry_name' => '化粧品メーカー'],
            ['industry_name' => 'コンピュータ・OA・通信機器メーカー'],
            ['industry_name' => '電気・家電メーカー'],
            ['industry_name' => '商社・卸'],
            ['industry_name' => '機械メーカー'],
            ['industry_name' => '食品メーカー'],
            ['industry_name' => '化学・石油メーカー'],
            ['industry_name' => '繊維メーカー'],
            ['industry_name' => 'その他製造業'],
            ['industry_name' => '精密・測定機器メーカー'],
            ['industry_name' => '旅行・ホテル・レジャー・外食'],
            ['industry_name' => '通信・情報サービス関連'],
            ['industry_name' => '産業機器・重電・設備メーカー'],
            ['industry_name' => 'その他'],
        ];
        DB::table('industries')->insert($data);
    }
}

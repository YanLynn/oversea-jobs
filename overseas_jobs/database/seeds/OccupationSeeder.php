<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['occupation_name' => 'エグゼクティブ/経営'],
            ['occupation_name' => 'IT関連（オープン・WEB系）'],
            ['occupation_name' => 'IT関連（汎用系）'],
            ['occupation_name' => 'IT関連（インフラ系）'],
            ['occupation_name' => 'IT関連（組込み/制御系）'],
            ['occupation_name' => 'IT関連（その他）'],
            ['occupation_name' => '電機（電気/電子/半導体）'],
            ['occupation_name' => '機械（自動車/プラント/精密機器）'],
            ['occupation_name' => 'メディカル/医薬/バイオ/素材/食品'],
            ['occupation_name' => '建築/土木/設備/不動産'],
            ['occupation_name' => 'コンサルティング'],
            ['occupation_name' => '総務/人事'],
            ['occupation_name' => '法務'],
            ['occupation_name' => '財務/会計'],
            ['occupation_name' => '事務系'],
            ['occupation_name' => 'カスタマーサービス'],
            ['occupation_name' => '金融/銀行/証券'],
            ['occupation_name' => '保険'],
            ['occupation_name' => '資産/不動産資産/不動産'],
            ['occupation_name' => 'その他金融関連職種'],
            ['occupation_name' => '営業'],
            ['occupation_name' => 'マーケティング/PR'],
            ['occupation_name' => '流通・小売/消費財/ファッション'],
            ['occupation_name' => 'ホテル/レジャー/外食系/旅行'],
            ['occupation_name' => '教育/トレーニング/語学系 '],
            ['occupation_name' => 'クリエイティブ(インターネット関連)'],
            ['occupation_name' => 'クリエイティブ(広告/出版/放送/映像関連)'],
            ['occupation_name' => 'クリエイティブ（その他)'],
            ['occupation_name' => '医療/福祉/介護'],
            ['occupation_name' => '官公庁/団体'],
            ['occupation_name' => 'その他の職種']
        ];
        DB::table('occupations')->insert($data);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['language_name' => '英語'],
            ['language_name' => '日本語'],
            ['language_name' => '中国語 – 北京語'],
            ['language_name' => '中国語 – 上海語'],
            ['language_name' => '中国語 – 広東語'],
            ['language_name' => '韓国語'],
            ['language_name' => 'ミャンマー語 '],
            ['language_name' => 'ベトナム語 '],
            ['language_name' => 'インドネシア語'],
            ['language_name' => 'マレー語 '],
            ['language_name' => 'カンボジア語'],
            ['language_name' => 'タイ語'],
            ['language_name' => 'ラオス語'],
            ['language_name' => 'フランス語 '],
            ['language_name' => 'ドイツ語'],
            ['language_name' => '朝鮮語'],
            ['language_name' => 'イタリア語'],
            ['language_name' => 'スペイン語'],
            ['language_name' => 'ポルトガル語'],
            ['language_name' => 'ポルトガル語 （ブラジル）'],
            ['language_name' => 'ロシア語'],
            ['language_name' => 'アラビア語'],
            ['language_name' => 'ヒンディー語'],
            ['language_name' => 'タガログ語'],
            ['language_name' => 'ハンガリー語'],
            ['language_name' => 'その他'],
        ];
        DB::table('languages')->insert($data);
    }
}

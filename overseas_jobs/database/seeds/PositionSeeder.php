<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['position_name' => '経営者'],
            ['position_name' => 'マネジメント層'],
            ['position_name' => 'チームリーダー'],
            ['position_name' => '一般社員'],
            ['position_name' => 'その他']
        ];
        DB::table('positions')->insert($data);
    }
}

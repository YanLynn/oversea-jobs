<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['employment_type_name' => '正社員'],
            ['employment_type_name' => '契約社員/嘱託社員'],
            ['employment_type_name' => '業務委託'],
            ['employment_type_name' => 'パートタイマー'],
            ['employment_type_name' => 'アルバイト'],
            ['employment_type_name' => '派遣社員'],
        ];
        DB::table('employment_types')->insert($data);
    }
}

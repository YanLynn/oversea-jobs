<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([OccupationSeeder::class, EmploymentTypeSeeder::class, IndustrySeeder::class, LanguageSeeder::class, CountrySeeder::class, PositionSeeder::class]);
        
        $data = [
            [
                'name' => "Admin",
                'email' => 'admin@gmail.com',
                'role_id' => 1,
                'password' => Hash::make('123123123'),
                'email_verified' => 1,
            ],
            [
                'name' => "recruiter",
                'email' => 'recruiter@gmail.com',
                'role_id' => 2,
                'password' => Hash::make('123123123'),
                'email_verified' => 1,
            ],
            [
                'name' => "Job Seeker",
                'email' => 'jobseeker@gmail.com',
                'role_id' => 3,
                'password' => Hash::make('123123123'),
                'email_verified' => 1,
            ]
        ];
        DB::table('users')->insert($data);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobseekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'user_id' => 3, 
                'jobseeker_number' => '0000001', 
                'country_id' => 1, 
                'jobseeker_name' => 'Job Seeker',
                'jobseeker_furigana_name' => '',
                'jobseeker_nick_name' => '',
                'face_image' => '0000001_face_image.png',
                'face_image_private_status' => 1,
                'dob' => '',
                'address' => 'Yangon',
                'phone' => '09123456789',
                'email' => 'jobseeker@gmail.com',
                'gender' => '',
                'skype_account' => 'Skype',
                'video' => 'https://www.youtube.com/watch?v=ktnOsnWut4o&list=RDktnOsnWut4o&start_radio=1',
                'final_education' => '',
                'current_situation' => '',
                'num_of_experienced_companies' => 5,
                'last_currency' => '$',
                'last_annual_income' => '',
                'self_pr' => '',
                'job_change_reason' => '',
                'job_search_activity' => '',
                'main_fact_when_change' => '',
                'desired_change_period' => '',
                'desired_location_1' => '',
                'desired_location_2' => '',
                'desired_location_3' => '',
                'desired_industry_status' => '',
                'desired_occupation_status' => '',
                'desired_salary_status' => '',
                'desired_currency' => '',
                'desired_min_annual_income' => '',
                'desired_max_annual_income' => '',
                'scout_setting_status' => '',
                'visa_status' => '',
                'visa_country' => '',
                'toeic_score' => '',
                'other_language_certificate' => '',
                'other_certificate' => '',
                'favourite_job_ids' => '',
                'record_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('jobseekers')->insert($data);
    }
}

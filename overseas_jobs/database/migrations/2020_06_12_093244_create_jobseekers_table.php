<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobseekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->increments('id');            
            $table->foreignId('user_id');
            $table->string('jobseeker_number',10)->nullable();
            $table->foreignId('country_id')->nullable();
            $table->string('jobseeker_name');
            $table->string('jobseeker_furigana_name');
            $table->string('jobseeker_nick_name')->nullable();
            $table->string('face_image')->nullable();
            $table->boolean('face_image_private_status')->default(0);
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50);
            $table->string('email', 50);
            $table->string('gender', 20)->nullable();
            $table->string('skype_account', 100)->nullable();
            $table->string('video', 100)->nullable();
            $table->string('final_education', 255)->nullable();
            $table->string('current_situation', 255)->nullable();
            $table->integer('num_of_experienced_companies')->nullable();
            $table->string('last_currency', 100)->nullable();
            $table->string('last_annual_income', 255)->nullable();
            $table->text('self_pr')->nullable();
            $table->string('job_change_reason', 255)->nullable(); 
            $table->string('job_search_activity', 255)->nullable(); 
            $table->string('main_fact_when_change', 255)->nullable(); 
            $table->string('desired_change_period', 255)->nullable();
            $table->string('desired_location_1', 255)->nullable();
            $table->string('desired_location_2', 255)->nullable();
            $table->string('desired_location_3', 255)->nullable();
            $table->boolean('desired_industry_status')->nullable();
            $table->integer('desired_industry_id')->default(0);
            $table->boolean('desired_occupation_status')->nullable();
            $table->integer('desired_occupation_id')->default(0);
            $table->boolean('desired_salary_status')->nullable();
            $table->string('desired_currency')->nullable();
            $table->string('desired_min_annual_income')->nullable();
            $table->string('desired_max_annual_income')->nullable();
            $table->boolean('scout_setting_status')->default(1);
            $table->boolean('visa_status')->nullable();
            $table->string('visa_country',50)->nullable();
            $table->string('toeic_score',50)->nullable();
            $table->text('other_language_certificate')->nullable();
            $table->text('other_certificate')->nullable();
            $table->string('favourite_job_ids')->nullable();
            $table->integer('record_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseekers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaterecruitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruiters', function (Blueprint $table) {
            $table->id();
            $table->string('recruiter_number',10)->nullable();
            $table->string('registered_place_type',10);
            $table->foreignId('user_id');
            $table->foreignId('country_id')->nullable();
            $table->string('recruiter_name');
            $table->string('recruiter_nick_name')->nullable();
            $table->date('establishment_date')->nullable();
            $table->string('representative_name')->nullable();
            $table->integer('num_of_employees')->nullable();
            $table->text('business_description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone1', 50);
            $table->string('phone2', 50)->nullable();
            $table->string('email', 50);            
            $table->string('website', 50);
            $table->string('logo', 50);            
            $table->string('video', 255)->nullable();
            $table->string('incharge_name', 100);
            $table->string('incharge_name_furigana', 100);
            $table->string('incharge_photo', 255)->nullable();
            $table->string('incharge_email', 50)->nullable();
            $table->string('account_id', 100)->nullable();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->text('company_pr')->nullable();
            $table->string('favourite_jobseeker_ids')->nullable();
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
        Schema::dropIfExists('recruiters');
    }
}

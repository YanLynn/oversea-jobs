<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_number',20)->nullable();
            $table->foreignId('recruiter_id');
            $table->foreignId('occupation_id');
            $table->foreignId('country_id');
            $table->string('recruiter_show_name')->nullable();
            $table->text('title')->nullable();
            $table->text('occupation_description')->nullable();
            $table->string('employment_types',255)->nullable();
            $table->text('job_description')->nullable();
            $table->text('qualification')->nullable();
            $table->text('allowance')->nullable();
            $table->text('job_start_date')->nullable();
            $table->text('job_location')->nullable();
            $table->text('message')->nullable();
            $table->string('other_keywords',255);
            // $table->set('other_keywords',['新卒歓迎','未経験歓迎','シニア歓迎','本社採用','語学不問']);
            $table->string('application_address');
            $table->string('job_post_status');
            $table->datetime('job_post_date');
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
        Schema::dropIfExists('jobs');
    }
}

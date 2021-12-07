<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id');
            $table->foreignId('position_id');
            $table->foreignId('employment_type_id');
            $table->text('job_location')->nullable();
            $table->text('main_duty')->nullable();
            $table->tinyInteger('from_year',false)->nullable();
            $table->tinyInteger('from_month',false)->nullable();
            $table->tinyInteger('to_year',false)->nullable();
            $table->tinyInteger('to_month',false)->nullable();
            $table->tinyInteger('current',false)->nullable();
            $table->tinyInteger('private_status',false)->nullable();
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
        Schema::dropIfExists('experience_histories');
    }
}

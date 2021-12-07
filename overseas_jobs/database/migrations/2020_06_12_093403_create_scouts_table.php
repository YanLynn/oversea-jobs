<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id');
            $table->foreignId('jobseeker_id');
            $table->foreignId('job_id');
            $table->string('management_number',20)->nullable();
            $table->string('scout_status');
            $table->timestamp('scouted_date')->useCurrent();
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
        Schema::dropIfExists('scouts');
    }
}

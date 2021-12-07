<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id');
            $table->string('school_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('degree')->nullable();
            $table->tinyInteger('from_year',false)->nullable();
            $table->tinyInteger('from_month',false)->nullable();
            $table->tinyInteger('to_year',false)->nullable();
            $table->tinyInteger('to_month',false)->nullable();
            $table->string('education_status')->nullable();
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
        Schema::dropIfExists('education_histories');
    }
}

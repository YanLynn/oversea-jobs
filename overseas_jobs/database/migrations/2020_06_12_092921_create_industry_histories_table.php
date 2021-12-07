<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id');
            $table->foreignId('industry_id');
            $table->foreignId('occupation_keyword_id');
            $table->foreignId('position_id');
            $table->integer('experience_year');
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
        Schema::dropIfExists('industry_histories');
    }
}

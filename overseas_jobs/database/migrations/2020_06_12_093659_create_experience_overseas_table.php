<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperienceOverseasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_overseas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id');
            $table->foreignId('country_id');
            $table->foreignId('position_id');
            $table->string('period', 100);
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
        Schema::dropIfExists('experience_overseas');
    }
}

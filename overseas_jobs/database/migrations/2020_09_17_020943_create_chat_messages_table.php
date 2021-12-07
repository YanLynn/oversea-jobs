<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
			$table->id();
			$table->foreignId('recruiter_id');
			$table->foreignId('jobseeker_id');
			$table->foreignId('speaker_id');
			$table->foreignId('speaker_role_id');
			$table->integer('scoutid_or_applyid');
			$table->string('type', 50);
			$table->text('message');
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
        Schema::dropIfExists('chat_messages');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessageReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_message_receivers', function (Blueprint $table) {
			$table->id();
			$table->foreignId('chat_message_id');
			$table->foreignId('receiver_id');
			$table->tinyInteger('receiver_role_id');
			// 1 for read, 0 for unread
			$table->tinyInteger('status')->default(0);
			$table->timestamp('email_sent_at')->nullable();
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
        Schema::dropIfExists('chat_message_receivers');
    }
}

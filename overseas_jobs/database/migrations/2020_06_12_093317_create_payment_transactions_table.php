<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('scoutid_or_applyid');
            $table->foreignId('recruiter_id');
            $table->string('payment_job_type');
            $table->string('payment_method');
            $table->string('invoice_number');
            $table->string('invoice_amount');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('actual_payment_date');
            $table->string('payment_amount');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('payment_transactions');
    }
}

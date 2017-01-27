<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookingdetails', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 10);
            $table->integer('event_id');
            $table->integer('user_id');
            $table->smallInteger('currency_id');
            $table->string('transaction_id', 30);
            $table->enum('transaction_status',array('pending','captured','verified'));
            $table->enum('order_status',array('pending','completed','error'));
            $table->string('transaction_message', 2000);
            $table->datetime('order_time');
            $table->datetime('captured_time');
            $table->datetime('verification_time');
            $table->string('barcode', 30);
            $table->double('total_amount');
            $table->datetime('deleted_at');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::drop('bookingdetails');
    }
}

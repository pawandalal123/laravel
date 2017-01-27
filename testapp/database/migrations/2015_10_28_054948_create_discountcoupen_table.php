<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountCoupenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('discount_coupen', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('ticket_id');
				$table->integer('event_id');
				$table->string('code',25)->nullable();
				$table->tinyInteger('mode');  //flat or percentage
				$table->tinyInteger('display');  //to be displayed on event page (will be used Later)
				$table->integer('from');  // Bulk discount applicable from 
				$table->integer('to');  // Bulk Discount applicable to 
				$table->integer('group');     // creating discounts in bulk
				$table->integer('quantity');
				$table->integer('remaining_quantity');
				$table->double('amount');
				$table->datetime('start_date');
				$table->datetime('end_date');
				$table->tinyInteger('status');  //enable disable
				$table->integer('created_by');
				$table->integer('modified_by');
				$table->timestamp('deleted_at');     
        });     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::drop('discount_coupen');
    }
}

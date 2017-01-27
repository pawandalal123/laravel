<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->string('description', 1000);
            $table->integer('event_id');
            $table->double('price');
            $table->integer('quantity');
            $table->smallInteger('min_order_quantity');
            $table->smallInteger('max_order_quantity');
            $table->datetime('start_date_time');
            $table->datetime('end_date_time');
            $table->boolean('status');
            $table->integer('total_sold');
            $table->integer('on_hold');
            $table->boolean('type');
            $table->smallInteger('order');
            $table->boolean('registration_form');
            $table->boolean('display_status');
            $table->boolean('sold_out');
            $table->smallInteger('currency_id');
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
        Schema::drop('tickets');
    }
}

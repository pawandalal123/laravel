<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderbreakagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderbreakages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 10);
            $table->integer('ticket_id');
            $table->smallInteger('quantity');
            $table->double('amount');
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
        Schema::drop('orderbreakages');
    }
}

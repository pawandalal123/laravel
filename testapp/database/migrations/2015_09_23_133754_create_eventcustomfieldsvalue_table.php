<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventcustomfieldsvalueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventcustomfieldsvalue', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('event_custom_fields_id');
            $table->string('value', 2000);
            $table->smallInteger('position');
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
        Schema::drop('eventcustomfieldsvalue');
    }
}

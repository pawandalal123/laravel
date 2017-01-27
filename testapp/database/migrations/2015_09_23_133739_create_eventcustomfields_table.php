<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventcustomfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventcustomfields', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('ticket_id');
            $table->integer('common_custom_field_id');
            $table->string('name', 500);
            $table->enum('type',array('text','textarea','radio','select','date','file','checkbox'));
            $table->boolean('mandatory');
            $table->string('placeholder', 100);
            $table->smallInteger('sequence_order');
            $table->boolean('status');
            $table->mediumtext('multiple_value');
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
        Schema::drop('eventcustomfields');
    }
}

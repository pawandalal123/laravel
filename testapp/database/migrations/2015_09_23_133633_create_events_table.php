<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->datetime('start_date_time');
            $table->datetime('end_date_time');
            $table->smallInteger('timezone_id');
            $table->string('title', 1000);
            $table->text('description');
            $table->string('country', 300);
            $table->string('state', 300);
            $table->string('city', 300);
            $table->string('venue_name', 300);
            $table->string('address1', 1000);
            $table->string('address2', 1000);
            $table->double('latitude');
            $table->double('longitude');
            $table->string('pincode', 30);
            $table->string('banner_image', 1000);
            $table->string('url', 1000);
            $table->boolean('status');
            $table->boolean('event_mode');
            $table->string('category', 300);
            $table->boolean('private');
            $table->boolean('ticketsoldout');
            $table->boolean('popularity');
            $table->string('ipaddress', 100);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->datetime('deleted_at');
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
        Schema::drop('events');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventdetails', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->boolean('contact_display');
            $table->string('contact_name', 200);
            $table->string('contact_mobile', 30);
            $table->string('contact_website_url', 1000);
            $table->string('facebook_link', 1000);
            $table->string('google_link', 1000);
            $table->string('twitter_link', 1000);
            $table->string('linkedin_link', 1000);
            $table->string('map_url', 2000);
            $table->boolean('password_required');
            $table->string('password', 100);
            $table->enum('booknow_button_value',array('Book Now','Register','Buy Ticket','Donate'));
            $table->boolean('show_map');
            $table->boolean('show_remaining_ticket');
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
        Schema::drop('eventdetails');
    }
}

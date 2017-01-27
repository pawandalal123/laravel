<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchdulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('schdules', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('event_id');
                $table->string('schedule_name');
                $table->datetime('start_date_time'); 
                $table->datetime('end_date_time'); 
                $table->string('venue_name'); 
                $table->string('latitude'); 
                $table->string('longitude'); 
                $table->string('map_url');
                $table->string('venue_name'); 
                $table->string('address1'); 
                $table->string('address2'); 
                $table->string('city'); 
                $table->string('state'); 
                $table->string('pincode'); 
                $table->string('country');
                $table->integer('status');
                $table->integer('created_by');
                $table->integer('modified_by');
                $table->timestamp('deleted_at');
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                
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
    }
}

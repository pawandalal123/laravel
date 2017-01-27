<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitydetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('citydetails', function(Blueprint $table) {
            $table->increments('id');
            $table->string('country',300);
            $table->string('state',300);
            $table->string('city',300);
            $table->string('lat',45);
            $table->string('long', 45);            
            $table->boolean('status');            
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
        //
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimezonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('timezones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',500)->nullable();
            $table->string('zone',500)->nullable();
            $table->string('timezone', 500)->nullable();
            $table->tinyInteger('status');
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
		Schema::drop('timezones');
    }
}

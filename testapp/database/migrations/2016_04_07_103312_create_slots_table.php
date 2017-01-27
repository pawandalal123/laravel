<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('slots', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('schedule_id');              
                $table->datetime('start_date_time'); 
                $table->datetime('end_date_time');                 
                $table->integer('status');
                $table->integer('created_by');
                $table->integer('modified_by');
                $table->datetime('deleted_at');
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

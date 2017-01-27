<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recurringevents', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('event_id');
                $table->tinyInteger('type');
                $table->tinyInteger('occurrence');       
                $table->string('dates',20);
                $table->string('start_time',20);
                $table->string('end_time',20); 
                $table->string('days',45); 
                $table->string('name',64);                                           
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

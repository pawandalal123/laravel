<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventpermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('eventpermissions', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('event_id');  
                $table->string('permission');                            
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

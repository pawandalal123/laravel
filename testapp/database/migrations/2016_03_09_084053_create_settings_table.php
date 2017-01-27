<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
       Schema::create('settings', function (Blueprint $table) {
                
                $table->increments('id');
                $table->integer('event_id');
                $table->string('type');
                 $table->tinyInteger('value');                             
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

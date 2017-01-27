<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommoncustomfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commoncustomfields', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 500);
            $table->enum('type',array('text','textarea','radio','select','date','file','checkbox'));
            $table->boolean('mandatory');
            $table->string('placeholder', 100);
            $table->boolean('status');
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
        Schema::drop('commoncustomfields');
    }
}

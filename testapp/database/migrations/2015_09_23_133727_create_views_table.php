<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 10);
            $table->integer('event_id');
            $table->integer('viewed_by_user_id');
            $table->integer('viewed_user_id');
            $table->enum('type',array('event','user_profile'));
            $table->string('ip_address', 30);
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
        Schema::drop('views');
    }
}

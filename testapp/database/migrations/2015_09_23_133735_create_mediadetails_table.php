<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediadetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediadetails', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 10);
            $table->integer('event_id');
            $table->string('path', 1000);
            $table->string('name', 200);
            $table->mediumtext('description');
            $table->boolean('type');
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
        Schema::drop('mediadetails');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetails', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_id', 10);
            $table->integer('event_id');
            $table->integer('user_id');
            $table->smallInteger('hold_time');
            $table->boolean('on_hold');
            $table->boolean('is_free');
            $table->mediumtext('details');
            $table->boolean('all_info');
            $table->smallInteger('currency_id');
            $table->datetime('order_time');
            $table->double('total_amount');
            $table->boolean('clicked');
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
        Schema::drop('orderdetails');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('ticket_widget', function (Blueprint $table) {
			    
				$table->increments('id');
				$table->integer('event_id');
				$table->integer('ticket_id');
				$table->tinyInteger('show_ticket_description'); 
				$table->string('top_background_color',30); 
				$table->string('text_color',30); 
				$table->string('booknow_button_color',30); 
				$table->string('redirect_url',500);
				$table->tinyInteger('show_banner');
				$table->tinyInteger('show_logo');
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

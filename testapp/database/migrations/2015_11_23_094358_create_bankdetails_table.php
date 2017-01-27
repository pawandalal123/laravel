<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

		Schema::create('bankdetails', function (Blueprint $table) {
			    
				$table->increments('id');
				$table->integer('user_id');
				$table->string('account_name');
				$table->string('bank_name'); 
				$table->string('branch_address',300); 
				$table->string('account_number',64); 
				$table->string('ifsc',30); 
				$table->tinyInteger('type');				
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

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userdetails', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('profile_url', 2000);
            $table->string('facebook_url', 2000);
            $table->string('linkedin_url', 2000);
            $table->string('googleplus_url', 2000);
            $table->string('organization_name', 200);
            $table->string('organization_logo', 1000);
            $table->string('organization_banner_image', 1000);
            $table->mediumtext('about_organization');
            $table->string('organization_url', 2000);
            $table->string('organization_twitter_url', 2000);
            $table->string('organization_linkedin_url', 2000);
            $table->string('organization_googleplus_url', 2000);
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
        Schema::drop('userdetails');
    }
}

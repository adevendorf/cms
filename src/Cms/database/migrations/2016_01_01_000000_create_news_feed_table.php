<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsfeed', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('page_id');
            $table->tinyInteger('custom_headline')->default(0);
            $table->tinyInteger('custom_synopsis')->default(0);
            $table->tinyInteger('custom_url')->default(0);
            $table->tinyInteger('custom_image')->default(0);
            $table->unsignedInteger('image_id')->nullable();
            $table->string('headline')->nullable();
            $table->string('redirect_url')->nullable();
            $table->tinyInteger('new_window')->default(0);
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('order')->default(0);

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newsfeed');
    }
}

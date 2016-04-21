<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{

    public function up()
    {
        
        Schema::create('images', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('owner_id')->nullable();
            $table->unsignedInteger('owner_type')->nullable();
            $table->unsignedInteger('asset_id')->nullable();
            $table->string('caption')->nullable();

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

        });
    }


    public function down()
    {
        Schema::drop('images');
    }
}

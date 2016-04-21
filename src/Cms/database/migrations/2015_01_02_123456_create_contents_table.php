<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{

    public function up()
    {
        Schema::create('contents', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('version')->default(0);
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('order')->default(999);
            $table->unsignedInteger('resource_id')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->string('type')->nullable();
            $table->text('data')->nullable();
            $table->string('styling')->nullable();
            $table->string('template')->default('default');

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);
        });
    }

    public function down()
    {
        Schema::drop('contents');
    }
}

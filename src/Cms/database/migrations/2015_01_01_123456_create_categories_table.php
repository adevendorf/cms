<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('group');
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('parent')->nullable();
            $table->unsignedInteger('image_id')->nullable();

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

            $table->index(['group', 'slug'], 'idx_extensions');
        });
    }

    public function down()
    {
        Schema::drop('categories');
    }
}

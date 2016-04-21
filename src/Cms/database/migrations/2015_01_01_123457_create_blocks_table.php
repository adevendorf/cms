<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{

    public function up()
    {
        Schema::create('blocks', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('type')->default('content');
            $table->string('layout_action')->default('override');
            $table->string('title')->nullable();
            $table->string('styling')->nullable();
            $table->text('data')->nullable();
            $table->string('css_id')->nullable();
            $table->string('css_class')->nullable();
            $table->unsignedInteger('image_id')->nullable();

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
        Schema::drop('blocks');
    }
}

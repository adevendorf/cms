<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCropsizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cropsizes', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('name');
            $table->string('max_dimension');
            $table->string('aspect_ratio');

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
        Schema::drop('cropsizes');
    }
}

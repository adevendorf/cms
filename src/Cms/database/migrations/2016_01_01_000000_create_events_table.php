<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('title');
            $table->string('type')->default('info');
            $table->unsignedInteger('details_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();

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
        Schema::drop('events');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('domain')->unique();
            $table->string('name');
            $table->integer('owner')->nullable();
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
        Schema::drop('sites');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('primary_dir')->nullable()->index();
            $table->string('url')->index();
            $table->unsignedInteger('page_id')->nullable();
            $table->unsignedTinyInteger('redirect')->default(0);
            $table->string('redirect_to')->nullable();

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
        Schema::drop('routes');
    }
}

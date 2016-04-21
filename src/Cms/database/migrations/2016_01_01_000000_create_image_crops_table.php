<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_crops', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('image_id');
            $table->string('name')->default('default');
            $table->unsignedInteger('crop_x')->default(0);
            $table->unsignedInteger('crop_y')->default(0);
            $table->unsignedInteger('crop_width')->default(100);
            $table->unsignedInteger('crop_height')->default(100);
            $table->unsignedInteger('max_dimension')->nullable();
            $table->string('aspect_ratio')->nullable();

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
        Schema::drop('image_crops');
    }
}

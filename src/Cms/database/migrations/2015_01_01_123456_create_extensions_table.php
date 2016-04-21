<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionsTable extends Migration
{

    public function up()
    {
        
        Schema::create('extensions', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

            $table->index(['type', 'key']);
        });
            
    }


    public function down()
    {
        Schema::drop('extensions');
    }
}

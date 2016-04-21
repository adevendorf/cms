<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{


    public function up()
    {
        
        Schema::create('assets', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('original_filename');
            $table->string('path');
            $table->string('keywords')->nullable();
            $table->text('caption')->nullable();
            $table->string('type');
            $table->unsignedInteger('max_dimension')->nullable();
            $table->string('cms_filename');
            $table->string('crop_preference')->default('center');
            $table->string('folder')->default('recent');

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

            $table->index(['type', 'folder'], 'idx_assets');
        });

//        DB::statement('ALTER TABLE cms_assets ADD FULLTEXT(keywords)');
    }


    public function down()
    {
        Schema::drop('assets');
    }
}

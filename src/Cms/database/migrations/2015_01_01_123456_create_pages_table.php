<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('original_id')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();

            $table->string('type')->default('page');
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('status')->default('hidden');


            $table->tinyInteger('allow_comments')->default(0)->unsigned();
            $table->tinyInteger('goog_no_index')->default(0)->unsigned();
            $table->tinyInteger('goog_index')->default(1)->unsigned();
            $table->tinyInteger('goog_no_follow')->default(0)->unsigned();
            $table->tinyInteger('goog_follow')->default(1)->unsigned();

            $table->string('template_name')->default('default');
            $table->tinyInteger('require_https')->default(0)->unsigned();
            $table->tinyInteger('ignore_in_search')->default(0)->unsigned();
            $table->tinyInteger('ignore_in_sitemap')->default(0)->unsigned();


            $table->unsignedInteger('author_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_publish')->nullable();
            $table->timestamp('scheduled_unpublish')->nullable();

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

            $table->index(['original_id', 'type', 'scheduled_publish', 'scheduled_unpublish'], 'idx_pages');
        });
    }


    public function down()
    {
        Schema::drop('pages');
    }
}

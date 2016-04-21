<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_blocks', function(Blueprint $table)
        {
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('version')->default(0);
            $table->index(['page_id', 'block_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page_blocks');
    }
}

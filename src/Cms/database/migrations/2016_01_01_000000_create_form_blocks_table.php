<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_blocks', function(Blueprint $table)
        {
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('block_id');
            $table->index(['form_id', 'block_id']);
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
        Schema::drop('form_blocks');
    }
}

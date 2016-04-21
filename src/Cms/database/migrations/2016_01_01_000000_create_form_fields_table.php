<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('question')->nullable();
            $table->string('question_type')->nullable();
            $table->text('options')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('encrypt')->default(false);
            $table->string('validation_msg')->nullable();
            $table->unsignedInteger('order')->default(999);

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);
        });

        Schema::table('form_fields', function(Blueprint $table)
        {
//            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade')->onUpdate('cascade');
//            $table->index(['form_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_fields');
    }
}

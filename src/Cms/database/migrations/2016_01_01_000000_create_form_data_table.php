<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_data', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->integer('form_submission_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->string('answer_type');
            $table->string('answer_string')->nullable();
            $table->text('answer_text')->nullable();
            $table->integer('answer_int')->nullable();
            $table->boolean('encrypted')->default(false);

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
        Schema::drop('form_data');
    }
}

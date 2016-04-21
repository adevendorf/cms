<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_ip')->nullable();
            $table->unsignedTinyInteger('accepted')->default(0);
            $table->text('notes')->nullable();

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);
        });

        Schema::table('form_submissions', function(Blueprint $table)
        {
//            $table->foreign('form_id')->references('id')->on('forms')->onUpdate('cascade')->onDelete('cascade');
            $table->index(['form_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_submissions');
    }
}

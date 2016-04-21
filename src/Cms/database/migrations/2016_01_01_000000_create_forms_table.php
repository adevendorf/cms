<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('submission_uuid');
            $table->tinyInteger('redirect_to_page')->default(1)->unsigned();
            $table->unsignedInteger('redirect_page_id')->nullable();
            $table->string('redirect_url')->nullable();
            $table->tinyInteger('send_email')->default(0)->unsigned();

            $table->string('email_to')->nullable();
            $table->string('submit_text')->default('Submit');
            $table->tinyInteger('save_data')->default(0)->unsigned();
            $table->string('save_to')->nullable();
            $table->string('save_to_name')->nullable();
            $table->tinyInteger('locked')->default(0)->unsigned();
            $table->unsignedInteger('started')->default(0);
            $table->unsignedInteger('completed')->default(0);

            $commons = new \Cms\Helpers\MigrationCommons();
            $commons->addCommonFields($table);

            $table->index(['submission_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('forms');
    }
}

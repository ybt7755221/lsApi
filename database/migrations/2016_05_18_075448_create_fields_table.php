<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields',function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('key');
            $table->string('params');
            $table->tinyInteger('publish');
            $table->char('field_type', 10);
            $table->integer('user_id');
            $table->integer('hits')->default(0);
            $table->index('key', 'publish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields');
    }
}

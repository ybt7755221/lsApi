<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link', function (Blueprint $table) {
           $table->increments('id');
           $table->string('url');
           $table->string('title');
           $table->string('thumb');
           $table->string('description');
           $table->enum('status',['show','hidden']);
           $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link');
    }
}

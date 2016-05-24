<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('fid')->default(0);
            $table->string('cat_name');
            $table->text('description');
            $table->string('cat_image', 100);
            $table->tinyInteger('sort');
            $table->enum('display',['show', 'hidden']);
            $table->string('path', 20);
            $table->enum('type',['local','single','link']);
            $table->string('url',100);
            $table->timestamp('created_at');
            $table->index('sort', 'display', 'path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category');
    }
}

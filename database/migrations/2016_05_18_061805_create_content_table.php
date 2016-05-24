<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('thumb');
            $table->integer('user_id');
            $table->text('body');
            $table->tinyInteger('comment_status');
            $table->tinyInteger('state');
            $table->tinyInteger('cat_id');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->index('title','user_id', 'updated_at', 'comment_status');
            $table->index(['state', 'cat_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content');
    }
}

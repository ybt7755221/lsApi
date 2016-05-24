<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id');
            $table->integer('user_id');
            $table->string('user_email');
            $table->char('user_ip', 15);
            $table->text('content');
            $table->timestamp('created_at');
            $table->enum('status',['display', 'hidden', 'remove']);
            $table->index('content_id', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comment');
    }
}

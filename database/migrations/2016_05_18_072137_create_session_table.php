<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session', function (Blueprint $table) {
            $table->string('session_id');
            $table->tinyInteger('client_id');
            $table->tinyInteger('guest');
            $table->timestamp('time');
            $table->mediumText('data');
            $table->integer('user_id');
            $table->primary('session_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('session');
    }
}

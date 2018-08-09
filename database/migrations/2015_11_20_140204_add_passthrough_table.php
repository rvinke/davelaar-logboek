<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassthroughTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passthroughs', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('log_id');
            $t->integer('passthrough_type_id');
            $t->integer('count');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('passthroughs');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorplans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floorplans', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('project_id');
            $t->integer('floor_id');
            $t->string('filename');
            $t->integer('xmax');
            $t->integer('ymax');
            $t->integer('minzoom');
            $t->integer('maxzoom');
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
        Schema::drop('floorplans');
    }
}

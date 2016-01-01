<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEisAndWandVloerToLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function(Blueprint $t){
            $t->integer('eis')->after('brandklep_id');
            $t->integer('oppervlak_type_id')->after('brandklep_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function(Blueprint $t){
            $t->dropColumn('eis');
            $t->dropColumn('oppervlak_type_id');
        });
    }
}

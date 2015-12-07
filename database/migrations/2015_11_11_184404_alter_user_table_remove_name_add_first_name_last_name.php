<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTableRemoveNameAddFirstNameLastName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $t){
            $t->dropColumn('name');
            $t->string('first_name', 50)->after('id');
            $t->string('last_name', 50)->after('first_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $t){
            $t->dropColumn('first_name');
            $t->dropColumn('last_name');
            $t->string('name')->after('id');
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsFloors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['floors', 'clients', 'fire_dampers', 'passthrough_types', 'systems'];

        foreach($tables as $table){
            Schema::table($table, function(Blueprint $t){
                $t->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['floors', 'clients', 'fire_dampers', 'passthrough_types', 'systems'];

        foreach($tables as $table) {
            Schema::table($table, function(Blueprint $t){
                $t->dropColumn('created_at');
                $t->dropColumn('updated_at');
            });
        }

    }
}

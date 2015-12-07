<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owner = new \App\Models\Role();
        $owner->name         = 'opdrachtgever';
        $owner->display_name = 'Opdrachtgever'; // optional
        $owner->description  = 'De opdrachtgever van een project'; // optional
        $owner->save();

        $admin = new \App\Models\Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrator'; // optional
        $admin->description  = 'Admin-gebruiker'; // optional
        $admin->save();

        $mw = new \App\Models\Role();
        $mw->name         = 'medewerker';
        $mw->display_name = 'Medewerker'; // optional
        $mw->description  = 'Normale medewerker'; // optional
        $mw->save();
    }
}

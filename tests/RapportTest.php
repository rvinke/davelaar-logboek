<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RapportTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRapportView()
    {

        $user = \App\User::findOrFail(1);

        $this->be($user);
        $this->visit('project/109/rapport')
            ->see('11327/13009/13082 HUIZE WINTERDIJK');
    }

    public function testRapportViewAsClient()
    {

        $user = \App\User::findOrFail(3);

        $this->be($user);
        $this->visit('project/113/rapport')
            ->see('11024 DE VALENTIJN');
    }
}

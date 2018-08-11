<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends BrowserKitTestCase
{

    use WithoutMiddleware;

    public function testProjectList()
    {
        $this->visit('/api/v1/projecten')
            ->see('{"naam":"11024 DE VALENTIJN",');
    }

    public function testRapportList()
    {

        $user = factory(App\User::class)->create();

        $this->actingAs($user)
            ->withSession(['id' => 3])
            ->visit('api/v1/rapporten')
            ->see('{"draw":0,"recordsTotal":0,"');
    }

    public function testSubdatabases()
    {

        $subdatabases = ['client', 'firedamper', 'floor', 'passthroughType', 'system', 'location'];

        foreach ($subdatabases as $subdatabase) {
            $this->visit('api/v1/subdatabase/'.$subdatabase)
                ->see('{"draw":0,"recordsTotal":');
        }
    }
}

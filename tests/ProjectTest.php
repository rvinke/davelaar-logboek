<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testProjectView()
    {
        $user = \App\User::findOrFail(1);

        $this->be($user);
        $this->visit('project/114')
            ->see('11386 LINGEWAARDE TIEL');
    }

    public function testProjectViewAsClient()
    {
        $user = \App\User::findOrFail(3);

        $this->be($user);
        $this->get('project/114')
            ->assertResponseStatus('403');
    }
}

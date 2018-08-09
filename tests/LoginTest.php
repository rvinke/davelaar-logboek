<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginPage()
    {
        $this->visit('/')
            ->see('Welkom bij het Logboek van Davelaarbouw B.V.');
    }

    public function testLogin()
    {
        $this->visit('/login')
            ->type('r.vinke@gmail.com', 'email')
            ->type('rvmmdnw1', 'password')
            ->press('Login')
            ->see('Welkom in het logboek van Davelaarbouw B.V.');
    }

    public function testWrongLogin()
    {
        $this->visit('/login')
            ->type('r.vinke@gmail.com', 'email')
            ->type('aaaa', 'password')
            ->press('Login')
            ->see('Deze combinatie van e-mailadres en wachtwoord is niet geldig.');
    }

    public function testLoginAndLogout()
    {
        $this->visit('/login')
            ->type('r.vinke@gmail.com', 'email')
            ->type('rvmmdnw1', 'password')
            ->press('Login')
            ->click('Uitloggen')
            ->see('Welkom bij het Logboek');
    }
}

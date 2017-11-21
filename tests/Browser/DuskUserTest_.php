<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// @todo unable to verify test due to XAMPP conflict

class ExampleTest extends DuskTestCase
{
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
          $browser->visit('/')
            ->clickLink('Register')
            ->assertSee('Register')
            ->value('Joseph', 'first_name')
            ->value('Silverman', 'last_name')
            ->value('joseph@gmail.com', 'email')
            ->value('joseph', 'password')
            ->value('joseph', 'password_confirmation')
            ->click('button[type="submit"]')
            ->assertPathIs('/home')
            ->assertSee('Purchases');
        });
    }
}
<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthroizedTest extends TestCase
{
    public function testLoggedOut()
    {
      $this->visit('/purchases')
        ->see('Login')
        ->dontSee('No records found');
    }

    public function testLoggedIn()
    {
      $user = factory('App\User')->make();

      $this->actingAs($user)
        ->visit('/purchases')
        ->see('Purchases');
    }
}

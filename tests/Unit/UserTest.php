<?php

namespace Tests\Unit;

use App\Email;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
  use DatabaseTransactions;

  public function createUser($email) {
    $user = factory('App\User')->create([
      'email' => $email,
    ]);

    factory('App\Email')->create([
      'user_id' => $user->id,
      'email_address' => $user->email,
      'is_default' => 1
    ]);

    return $user;
  }

  public function createUserAndEmails ($primary, $secondary) {
    $user = factory('App\User')->create([
      'email' => $primary,
    ]);

    factory('App\Email')->create([
      'user_id' => $user->id,
      'email_address' => $user->email,
      'is_default' => 1
    ]);

    factory('App\Email')->create([
      'user_id' => $user->id,
      'email_address' => $secondary,
    ]);

    return $user;
  }

  public function testRegistration()
  {
    $this->visit('/')
      ->see('Login')
      ->click('Register')
      ->type('Joseph', 'first_name')
      ->type('Silverman', 'last_name')
      ->type('joseph@gmail.com', 'email')
      ->type('joseph', 'password')
      ->type('joseph', 'password_confirmation')
      ->press('Register')
      ->seePageIs('/home');
  }

  public function testCreateEmail()
  {
    $user = $this->createUser('joseph@gmail.com');

    $this->actingAs($user)
      ->visit('/emails/create')
      ->see('Add an Email')
      ->type('joseph@jsilverman.ca', 'email_address')
      ->select(0, 'is_default')
      ->press('Submit')
      ->visit('/emails')
      ->see('joseph@jsilverman.ca');
  }

  public function testDefaultEmail()
  {
    $user = $this->createUser('joseph@gmail.com');

    $this->actingAs($user)
      ->visit('/emails/create')
      ->see('Add an Email')
      ->type('joseph@jsilverman.ca', 'email_address')
      ->select(1, 'is_default')
      ->press('Submit')
      ->visit('/emails')
      ->see('joseph@jsilverman.ca')
      ->assertEquals(1, Email::where('email_address', 'joseph@jsilverman.ca')->pluck('is_default')->first());
  }

  public function testEditEmail()
  {
    $primary = 'joseph@gmail.com';
    $secondary = 'joseph@jsilverman.ca';

    $user = $this->createUserAndEmails($primary, $secondary);

    $this->actingAs($user)
      ->visit('/emails')
      ->see($primary)
      ->see($secondary);

    $email_id = Email::where('email_address', $secondary)->pluck('id')->first();

    $this->visit('/emails/'.$email_id.'/edit')
      ->see('Edit Email')
      ->type('j@jsilverman.ca', 'email_address')
      ->select(1, 'is_default')
      ->press('Submit')
      ->visit('/emails')
      ->see($primary)
      ->see('j@jsilverman.ca');
  }

  public function testDeleteEmail()
  {
    $primary = 'joseph@gmail.com';
    $secondary = 'joseph@jsilverman.ca';

    $user = $this->createUserAndEmails($primary, $secondary);

    $this->actingAs($user)
      ->visit('/emails')
      ->see($primary)
      ->see($secondary)
      ->press('Delete')
      ->see($primary)
      ->assertTrue(true);

      // @todo dusk required to test this condition as a delay is need to catch the action
      //->dontSee('joseph@jsilverman.ca');
  }
}
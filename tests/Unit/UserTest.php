<?php

namespace Tests\Unit;

use App\Email;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    // @todo handles authentication and login as db transaction required for testing

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
      $this->testRegistration();
      $this->visit('/emails/create')
        ->see('Add an Email')
        ->type('joseph@jsilverman.ca', 'email_address')
        ->select(0, 'is_default')
        ->press('Submit')
        ->visit('/emails')
        ->see('joseph@jsilverman.ca');
    }

    public function testDefaultEmail()
    {
      $this->testRegistration();
      $this->visit('/emails/create')
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
      $this->testCreateEmail();

      $this->visit('/emails')
        ->see('joseph@gmail.com')
        ->see('joseph@jsilverman.ca');

      $email_id = Email::where('email_address', 'joseph@jsilverman.ca')->pluck('id')->first();

      $this->visit('/emails/'.$email_id.'/edit')
        ->see('Edit Email')
        ->type('j@jsilverman.ca', 'email_address')
        ->select(1, 'is_default')
        ->press('Submit')
        ->visit('/emails')
        ->see('joseph@gmail.com')
        ->see('j@jsilverman.ca');
    }

    public function testDeleteEmail()
    {
      $this->testCreateEmail();

      $this->visit('/emails')
        ->see('joseph@gmail.com')
        ->see('joseph@jsilverman.ca')
        ->press('Delete')
        ->see('joseph@gmail.com')
        ->dontSee('joseph@jsilverman.ca');
    }
}

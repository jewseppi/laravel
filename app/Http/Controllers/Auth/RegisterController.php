<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Email;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Webpatser\Uuid\Uuid;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'id' => 'uuid',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\User
   */
  protected function create(array $data)
  {
    $user_id = Uuid::generate(4)->string;
    $email_id = Uuid::generate(4)->string;

    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email_address = strtolower($data['email']);

    DB::beginTransaction();

    try {
      $email = new Email;
      $email->id = $email_id;
      $email->user_id = $user_id;
      $email->email_address = $email_address;
      $email->is_default = true;
      $email->save();
    } catch(ValidationException $e)

    {
      DB::rollback();
      return Redirect::to('/register')
        ->withErrors( $e->getErrors() )
        ->withInput();
    } catch(\Exception $e)

    {
      DB::rollback();
      throw $e;
    }

    try {
      $user = User::create([
        'id' => $user_id,
        'name' => $first_name.' '.$last_name,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email_address,
        'password' => bcrypt($data['password']),
      ]);
    } catch(ValidationException $e)

    {
      DB::rollback();
      return Redirect::to('/register')
        ->withErrors( $e->getErrors() )
        ->withInput();
    } catch(\Exception $e)

    {
      DB::rollback();
      throw $e;
    }

    DB::commit();

    return $user;
  }
}

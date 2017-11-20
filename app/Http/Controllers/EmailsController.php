<?php

namespace App\Http\Controllers;

use App\User;
use App\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Webpatser\Uuid\Uuid;

class EmailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = Email::where('user_id', '=', auth()->user()->id)->paginate(5);
        return view('emails.index')->with('emails', $emails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'email_address' => 'required|string|email|max:255|unique:emails',
        'is_default' => 'boolean',
      ]);

      $user_id = auth()->user()->id;
      $email_address = $request->input('email_address');
      $is_default = $request->input('is_default');

      DB::beginTransaction();

      if ($is_default) {
        $email_id = Email::where('user_id', $user_id)
          ->where('is_default', 1)
          ->pluck('id')
          ->first();

        if ($email_id) {
          try {
            $record = Email::find($email_id);
            $record->is_default = 0;
            $record->save();
          } catch(ValidationException $e)

          {
            DB::rollback();
            return Redirect::to('/emails/create')
              ->withErrors( $e->getErrors() )
              ->withInput();
          } catch(\Exception $e)

          {
            DB::rollback();
            throw $e;
          }
        }
      }

      try {
        $email = new Email;
        $email->id = Uuid::generate(4)->string;
        $email->user_id = $user_id;
        $email->email_address = $email_address;
        $email->is_default = $is_default;
        $email->save();
      } catch(ValidationException $e)

      {
        DB::rollback();
        return Redirect::to('/emails/create')
          ->withErrors( $e->getErrors() )
          ->withInput();
      } catch(\Exception $e)

      {
        DB::rollback();
        throw $e;
      }

      DB::commit();
      return redirect('/emails')->with('success', 'Email Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $email = Email::find($id);
        return view('emails.show')->with('email', $email);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = Email::find($id);
        return view('emails.edit')->with('email', $email);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $email = Email::find($id);
        $address = $email->email_address;
        $email_address = $request->input('email_address');
        $attribute = $address == $email_address  ? '' : '|unique:emails';

        $this->validate($request, [
          'email_address' => 'required|string|email|max:255'.$attribute,
          'is_default' => 'boolean',
        ]);

        $is_default = $request->input('is_default');
        if (!isset($is_default)) $is_default = 1;

        DB::beginTransaction();

        $user_id = auth()->user()->id;
        if ($email->is_default != $is_default) {
          $email_id = Email::where('user_id', $user_id)
            ->where('is_default', 1)
            ->pluck('id')
            ->first();

          if ($email_id) {
            try {
              $record = Email::find($email_id);
              $record->is_default = 0;
              $record->save();
            } catch(ValidationException $e)

            {
              DB::rollback();
              return Redirect::to('/emails/'.$id.'/edit')
                ->withErrors( $e->getErrors() )
                ->withInput();
            } catch(\Exception $e)

            {
              DB::rollback();
              throw $e;
            }

          }

          try {
            $user = User::find($user_id);
            $user->email = $email_address;
            $user->save();
          } catch(ValidationException $e)

          {
            DB::rollback();
            return Redirect::to('/emails/'.$id.'/edit')
              ->withErrors( $e->getErrors() )
              ->withInput();
          } catch(\Exception $e)

          {
            DB::rollback();
            throw $e;
          }
        }

        try {
          $email->email_address = $email_address;
          $email->is_default = $is_default;
          $email->save();
        } catch(ValidationException $e)

        {
          DB::rollback();
          return Redirect::to('/emails/'.$id.'/edit')
            ->withErrors( $e->getErrors() )
            ->withInput();
        } catch(\Exception $e)

        {
          DB::rollback();
          throw $e;
        }

        DB::commit();
        return redirect('/emails')->with('success', 'Email Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $email = Email::find($id);

        DB::beginTransaction();

        if (!$email->is_default) {
          try {
            $email->delete();
          } catch(ValidationException $e)

          {
            DB::rollback();
            return Redirect::to('/emails')
              ->withErrors( $e->getErrors() )
              ->withInput();
          } catch(\Exception $e)

          {
            DB::rollback();
            throw $e;
          }

          DB::commit();
          return redirect('/emails')->with('success', 'Email Deleted');
        }

        return redirect('/emails')->with('error', 'Cannot delete primary account');
    }
}

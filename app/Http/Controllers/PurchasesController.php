<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Webpatser\Uuid\Uuid;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::where('user_id', '=', auth()->user()->id)->paginate(3);
        return view('purchases.index')->with('purchases', $purchases);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchases.create');
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
        'company_name' => 'required|string|max:255',
        'share_name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'quantity' => 'required|numeric',
      ]);

      $price = $request->input('price');
      $quantity = $request->input ('quantity');

      if (!$price > 0)
        return redirect('/purchases/create')
          ->with('error', 'Price must be greater than 0')
          ->withInput();

      elseif (!$quantity > 0)
        return redirect('/purchases/create')
          ->with('error', 'Quantity must be greater than 0')
          ->withInput();

      DB::beginTransaction();

      try {
        $purchase = new Purchase;
        $purchase->id = Uuid::generate(4)->string; // v4 uuid
        $purchase->company_name = $request->input('company_name');
        $purchase->share_name = $request->input('share_name');
        $purchase->price = $price;
        $purchase->quantity = $quantity;
        $purchase->total_investment = $price * $quantity;
        $purchase->certificate_number = Uuid::generate(5, $purchase->id, Uuid::NS_DNS)->string; // v5 (sha-1) uuid
        $purchase->user_id = auth()->user()->id;
        $purchase->save();
      } catch(ValidationException $e)

      {
        DB::rollback();
        return Redirect::to('/purchases/create')
          ->withErrors( $e->getErrors() )
          ->withInput();
      } catch(\Exception $e)

      {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return redirect('/purchases')->with('success', 'Purchase Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::find($id);
        return view('purchases.show')->with('purchase', $purchase);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::find($id);
        return view('purchases.edit')->with('purchase', $purchase);
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
        $this->validate($request, [
          'price' => 'required|numeric',
          'quantity' => 'required|numeric',
        ]);

        $price = $request->input('price');
        $quantity = $request->input ('quantity');

        if (!$price > 0)
          return redirect('/purchases')
            ->with('error', 'Price must be greater than 0')
            ->withInput();

        elseif (!$quantity > 0)
          return redirect('/purchases')
            ->with('error', 'Quantity must be greater than 0')
            ->withInput();

        DB::beginTransaction();

        try {
          $purchase = Purchase::find($id);
          $purchase->price = round($price, 10);
          $purchase->quantity = $quantity;

          // @todo remove to update total_investment
          // $purchase->total_investment = $price * $quantity;

          $purchase->save();
        } catch(ValidationException $e)

        {
          DB::rollback();
          return Redirect::to('/purchases/'.$id.'/edit')
            ->withErrors( $e->getErrors() )
            ->withInput();
        } catch(\Exception $e)

        {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return redirect('/purchases')->with('success', 'Purchase Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
          $purchase = Purchase::find($id);
          $purchase->delete();
        } catch(ValidationException $e)

        {
          DB::rollback();
          return Redirect::to('/purchases')
            ->withErrors( $e->getErrors() )
            ->withInput();
        } catch(\Exception $e)

        {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return redirect('/purchases')->with('success', 'Purchase Deleted');
    }
}

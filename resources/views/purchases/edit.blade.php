@extends('layouts.app')

@section('content')
    <div class="panel-heading">
      <h1>Edit Purchase</h1>
    </div>

    <div class="panel-body">
      @php
        $shares = [
            'Preferred' => 'Preferred',
            'A' => 'A',
            'B' => 'B' ];
      @endphp

      {!! Form::open(['action' => ['PurchasesController@update', $purchase->id], 'method' => 'POST']) !!}
          <div class="form-group">
            {{Form::label('company_name', 'Company Name')}}
            {{Form::text('company_name', $purchase->company_name, ['class' => 'form-control mb-6', 'placeholder' => 'Company'])}}

            {{Form::label('share_name', 'Share Name')}}
            {{Form::select('share_name', $shares, $purchase->share_name, ['class' => 'form-control mb-6', 'placeholder' => 'choose a share type'])}}

            {{Form::label('price', 'Price')}}
            {{Form::text('price', $purchase->price, ['class' => 'form-control mb-6', 'placeholder' => ''])}}

            {{Form::label('quantity', 'Quantity')}}
            {{Form::text('quantity', $purchase->quantity, ['class' => 'form-control mb-6', 'placeholder' => ''])}}

            {{Form::label('certificate_number', 'Certificate Number')}}
            {{Form::text('certificate_number', $purchase->certificate_number, ['class' => 'form-control mb-6', 'placeholder' => '', 'readonly' => 'true'])}}
          </div>
          {{Form::hidden('_method', 'PUT')}}
          {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
          <a href="{{action("PurchasesController@index")}}" class="btn btn-default pull-right">Go Back</a>
      {!! Form::close() !!}
    </div>
@endsection
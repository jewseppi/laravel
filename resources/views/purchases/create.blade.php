@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>Purchase Shares</h1>
    </div>

    <div class="panel-body">
        @php
            $shares = [
                'Preferred' => 'Preferred',
                'A' => 'A',
                'B' => 'B' ];
        @endphp

        {!! Form::open(['action' => 'PurchasesController@store', 'method' => 'POST']) !!}
            <div class="form-group">
                {{Form::label('company_name', 'Company Name')}}
                {{Form::text('company_name', '', ['class' => 'form-control mb-6', 'placeholder' => 'Company'])}}

                {{Form::label('share_name', 'Share Name')}}
                {{Form::select('share_name', $shares, 0, ['class' => 'form-control mb-6', 'placeholder' => 'choose a share type'])}}

                {{Form::label('price', 'Price')}}
                {{Form::text('price', '', ['class' => 'form-control mb-6', 'placeholder' => ''])}}

                {{Form::label('quantity', 'Quantity')}}
                {{Form::text('quantity', '', ['class' => 'form-control mb-6', 'placeholder' => ''])}}
            </div>
            {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
            <a href="{{action('PurchasesController@index')}}" class="btn btn-default pull-right">Go Back</a>
        {!! Form::close() !!}
    </div>
@endsection
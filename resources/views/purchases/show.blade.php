@extends('layouts.app')

@section('content')
    <div class="panel-heading" style="background: aliceblue; color: darkslateblue;">
        <h2 class="pull-left">{{$purchase->company_name}}</h2>
        <h2 class="pull-right">{{$purchase->share_name}}</h2>
        <div class="clear mb-1"></div>
    </div>
    <div class="panel-body">
        <h4>Price: ${{$purchase->price}}</h4>
        <h4>Qty: {{$purchase->quantity}}</h4>
        <h4>Total: ${{$purchase->total_investment}}</h4>

        <br>
        <hr>
        <h5 class="pull-left" style="margin:0;"> Purchased on {{$purchase->created_at->format('Y-m-d')}}</h5>
        <h5 class="pull-right" style="margin:0;">Certificate: {{$purchase->certificate_number}}</h5>
        <div class="clear mb-1"></div>

        <a href="{{action("PurchasesController@edit", ['id' => $purchase->id]) }}" class="btn btn-default">Edit</a>
        <a href="{{action("PurchasesController@index")}}" class="btn btn-default">Go Back</a>

        {!!Form::open(['action' => ['PurchasesController@destroy', $purchase->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>
@endsection
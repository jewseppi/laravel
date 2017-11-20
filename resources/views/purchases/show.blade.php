@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>{{$purchase->company_name}}</h1>
        <h3>{{$purchase->share_name}}</h3>
    </div>
    <div class="panel-body">
        <h4>{{$purchase->price}}</h4>
        <h4>{{$purchase->quantity}}</h4>
        <h4>{{$purchase->total_investment}}</h4>

        <br>
        <h6>{{$purchase->certificate_number}}</h6>
        <h6>Purchased on {{$purchase->created_at->format('Y-m-d')}}</h6>
        <hr>

        <a href="{{action("PurchasesController@edit", ['id' => $purchase->id]) }}" class="btn btn-default">Edit</a>
        <a href="{{action("PurchasesController@index")}}" class="btn btn-default">Go Back</a>

        {!!Form::open(['action' => ['PurchasesController@destroy', $purchase->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>
@endsection
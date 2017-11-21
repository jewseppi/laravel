@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>Purchases<a href="{{action("PurchasesController@create")}}" class="btn btn-success pull-right">Buy</a></h1>
    </div>

    <div class="panel-body">
        @if(count($purchases) > 0)
            @foreach($purchases as $purchase)
                <div class="well">
                    <h4 class="panel-title">
                        <div class="pull-left" style="margin:0;">{{$purchase->company_name}}</div>
                        <div class="pull-right">
                            <a href="{{action("PurchasesController@show", ['id' => $purchase->id]) }}">
                                ${{number_format($purchase->total_investment, 2, '.', ',')}}
                            </a>
                        </div>
                        <div class="clear"></div>
                    </h4>
                    <hr>
                    <div id="collapse-{{$purchase->id}}" class="panel-collapse collapse">
                        <h4>Share Type: {{$purchase->share_name}}</h4>
                        <h4>Price: ${{$purchase->price}}</h4>
                        <h4>Qty: {{$purchase->quantity}}</h4>
                        <h4>Total: ${{$purchase->total_investment}}</h4>
                        <hr>
                        <h5 class="pull-left" style="margin:0;"> Purchased on {{$purchase->created_at->format('Y-m-d')}}</h5>
                        <h5 class="pull-right" style="margin:0;">Certificate: {{$purchase->certificate_number}}</h5>
                    </div>
                    <div class="clear mb-1"></div>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapse-{{$purchase->id}}">Details</a>
                    <a href="{{action("PurchasesController@edit", ['id' => $purchase->id]) }}" class="btn btn-default">Edit</a>
                    {!!Form::open(['action' => ['PurchasesController@destroy', $purchase->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                    {!!Form::close()!!}
                </div>
            @endforeach
            {!! str_replace('/?', '?', $purchases->render()) !!}
        @else
            <p>No records found</p>
        @endif
    </div>
@endsection
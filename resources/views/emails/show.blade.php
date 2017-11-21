@extends('layouts.app')

@section('content')
    <div class="panel-heading" style="background: aliceblue; color: darkslateblue;">
        <h2>{{$email->email_address}}</h2>
    </div>
    <div class="panel-body">
        <h4>Default: {{$email->is_default}}</h4>

        <br>
        <h5>Updated on {{$email->updated_at->format('Y-m-d')}}</h5>
        <h5>Created on {{$email->created_at->format('Y-m-d')}}</h5>
        <hr>

        <a href="{{action("EmailsController@edit", ['id' => $email->id]) }}" class="btn btn-default">Edit</a>
        <a href="{{action("EmailsController@index")}}" class="btn btn-default">Go Back</a>

        {!!Form::open(['action' => ['EmailsController@destroy', $email->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>
@endsection
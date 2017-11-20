@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>{{$email->email_address}}</h1>
    </div>
    <div class="panel-body">
        <h4>Default: {{$email->is_default}}</h4>

        <br>
        <h6>Updated on {{$email->updated_at->format('Y-m-d')}}</h6>
        <h6>Created on {{$email->created_at->format('Y-m-d')}}</h6>
        <hr>

        <a href="{{action("EmailsController@edit", ['id' => $email->id]) }}" class="btn btn-default">Edit</a>
        <a href="{{action("EmailsController@index")}}" class="btn btn-default">Go Back</a>

        {!!Form::open(['action' => ['EmailsController@destroy', $email->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>
@endsection
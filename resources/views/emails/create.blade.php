@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>Add an Email</h1>
    </div>

    <div class="panel-body">
        {!! Form::open(['action' => 'EmailsController@store', 'method' => 'POST']) !!}
            <div class="form-group">
                {{Form::label('email_address', 'Email Address')}}
                {{Form::text('email_address', '', ['class' => 'form-control mb-6', 'placeholder' => ''])}}

                {{Form::label('is_default', 'Default')}}

                Yes: {{ Form::radio('is_default', 1) }}
                &nbsp;No: {{ Form::radio('is_default', 0, true) }}
            </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
        <a href="{{action('EmailsController@index')}}" class="btn btn-default pull-right">Go Back</a>
        {!! Form::close() !!}
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="panel-heading">
        <h1>Emails<a href="{{action("EmailsController@create")}}" class="btn btn-success pull-right">New</a></h1>
    </div>

    <div class="panel-body">
        @foreach($emails as $email)
            <div class="well{{ $email->is_default ? ' default' : '' }}">
                <div class="panel-title">
                    <a href="{{action("EmailsController@show", ['id' => $email->id])}}" class='panel-title__custom'>
                        {{$email->email_address}}
                    </a>
                    @if ($email->is_default)
                        <div class="pull-right">
                            <i class="fa fa-star"></i>
                        </div>
                    @endif
                </div>
                <hr>
                <div id="collapse-{{$email->id}}" class="panel-collapse collapse">
                    <h4>Default:
                        @if ($email->is_default)
                            <i class="fa fa-check"></i>
                        @else
                            <i class="fa fa-times"></i>
                        @endif
                    </h4>
                    <h4>Updated: {{$email->updated_at->format('Y-m-d')}}</h4>
                    <h4>Created: {{$email->created_at->format('Y-m-d')}}</h4>
                </div>
                <a class="btn btn-primary" data-toggle="collapse" href="#collapse-{{$email->id}}">Details</a>
                <a href="{{action("EmailsController@edit", ['id' => $email->id]) }}" class="btn btn-default">Edit</a>

                {!!Form::open(['action' => ['EmailsController@destroy', $email->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                {!!Form::close()!!}
            </div>
        @endforeach
        {{ $emails->render() }}
    </div>
@endsection
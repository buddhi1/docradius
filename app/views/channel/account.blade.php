@extends('admin.layouts.main')

@section('content')

{{ Form::open(array('url'=>'channel/makeaccount')) }}
{{ Form::label('lblpassword', 'Password') }}: {{ Form::password('password') }}
{{ Form::submit('Create') }}
{{ Form::close() }}

@stop
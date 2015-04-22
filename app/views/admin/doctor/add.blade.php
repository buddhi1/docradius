@extends('admin.layouts.main')

@section('content')
{{ Form::open(array('url'=>'admin/doctor/create')) }}
{{ Form::label('lblname', 'Doctor name') }}
{{ Form::close() }}
@stop
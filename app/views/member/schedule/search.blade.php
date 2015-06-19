@extends('member.layouts.main')

@section('content')

@if(Session::has('message'))
	<div>{{ Session::get('message') }}</div>
@endif
@if($errors->has())
<div>
	<ul>
		@foreach($errors->all() as $error)
			<li> {{ $error }} </li>
		@endforeach
	</ul>
</div>	
@endif
{{ Form::open(array('url'=>'member/schedule/searchbydoctor')) }}
<div>{{ Form::text('keyword', '', array('placeholder'=>'Doctor name')) }} {{ Form::submit('Search') }}</div>
{{ Form::close() }}
@stop
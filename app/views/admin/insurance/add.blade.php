@extends('admin.layouts.main')

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

{{ Form::open(array('url'=>'admin/insurance/create')) }}
	<div>{{ Form::label('', 'Insurance name') }}: {{ Form::text('name') }}</div>
	<div>{{ Form::submit('Add insurance') }}</div>
{{ Form::close() }}

@stop
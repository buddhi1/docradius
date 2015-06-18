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

{{ Form::open(array('url'=>'admin/insurance/update')) }}
	{{ Form::hidden('id', $insurance->id) }}
	<div>{{ Form::label('', 'Insurance name') }}: {{ Form::text('name', $insurance->name) }}</div>
	<div>{{ Form::submit('Save changes') }}</div>
{{ Form::close() }}

@stop
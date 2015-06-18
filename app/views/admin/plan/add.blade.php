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

{{ Form::open(array('url'=>'admin/insurancePlan/create')) }}
	<div>{{ Form::label('', 'Insurance name') }}: {{ Form::select('insurance_id', $insurances) }}</div>
	<div>{{ Form::label('', 'Insurance plan name') }}: {{ Form::text('name') }}</div>
	<div>{{ Form::submit('Add insurance plan') }}</div>
{{ Form::close() }}

@stop
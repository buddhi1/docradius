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

{{ Form::open(array('url'=>'admin/insurancePlan/update')) }}
	{{ Form::hidden('id', $plan->id) }}
	<div>{{ Form::label('', 'Insurance name') }}: {{ Form::select('insurance_id', $insurances, $plan->insurance_id) }}</div>
	<div>{{ Form::label('', 'Insurance plan name') }}: {{ Form::text('name', $plan->name) }}</div>
	<div>{{ Form::submit('Save changes') }}</div>
{{ Form::close() }}

@stop
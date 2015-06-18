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


<table border="1">
	<tr>
		<th>ID</th>
		<th>Insurance plan name</th>
		<th>Insurance</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	@foreach($plans as $plan)
	<tr>
		<td>{{$plan->id}}</td>
		<td>{{$plan->name}}</td>
		<td>{{$plan->insurance}}</td>
		{{ Form::open(array('url'=>'admin/insurancePlan/edit')) }}
		{{ Form::hidden('id', $plan->id) }}
		<td>{{ Form::submit('Edit') }}</td>
		{{ Form::close() }}		
		{{ Form::open(array('url'=>'admin/insurancePlan/destroy')) }}
		{{ Form::hidden('id', $plan->id) }}
		<td>{{ Form::submit('Delete') }}</td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
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
		<th>Insurance id</th>
		<th>Insurance name</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	@foreach($insurances as $insurance)
	<tr>
		<td>{{$insurance->id}}</td>
		<td>{{$insurance->name}}</td>
		{{ Form::open(array('url'=>'admin/insurance/edit')) }}
		{{ Form::hidden('id', $insurance->id) }}
		<td>{{ Form::submit('Edit') }}</td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/insurance/destroy')) }}
		{{ Form::hidden('id', $insurance->id) }}
		<td>{{ Form::submit('Delete') }}</td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
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
		<th>Doctor ID</th>
		<th>Doctor name</th>
		<th>Contact No.</th>
		<th>Account state</th>
		<th>Specialties</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	@foreach($doctors as $doctor)
	<tr>
		<td> {{ $doctor->id }} </td>
		<td> {{ $doctor->name }} </td>
		<td> {{ $doctor->tp }} </td>
		@if($doctor->state == 1)
		<td> Active </td>
		@elseif($doctor->state == 0)
		<td> Deactive </td>
		@endif
		<td> {{ json_decode($doctor->specialties }} </td>
		{{ Form::open(array('url'=>'admin/doctor/edit')) }}
		{{ Form::hidden('id', $doctor->id) }}
		<td> {{ Form::submit('Edit') }} </td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/doctor/destroy')) }}
		{{ Form::hidden('id', $doctor->id) }}
		<td> {{ Form::submit('Delete') }} </td>
		{{ Form::close() }}
	</tr> 
	@endforeach
</table>
@stop
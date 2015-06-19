@extends('member.layouts.main')

@section('content')

{{Form::open(array('url'=>'member/doctor/appointments'))}}
Appointment Date: {{Form::text('app_date', null)}}
{{Form::submit('Search')}}
{{Form::close()}}

<table border="1">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Telephone No.</th>
		<th>Gender</th>
		<th>Channelling Time</th>
		<th>Hospital</th>
	</tr>
	@foreach($apps as $app)
	<tr>
		<td> {{ $app->name }} </td>
		<td> {{ $app->email }} </td>
		<td> {{ $app->patient_tp }} </td>
		@if($app->sex === 0)
			<td>Male</td>
		@elseif($app->sex === 1)
			<td>Female</td>
		@endif
		<td> {{ $app->time }} </td>
		<td> {{ $app->hospital }} </td>
	</tr> 
	@endforeach
</table>

@stop
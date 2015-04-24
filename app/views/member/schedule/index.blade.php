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

<table border="1">
	<tr>
		<th>Schedule ID</th>
		<th>Day</th>
		<th>Start time</th>
		<th>End time</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	<?php $days = ["sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]; ?>
	@foreach($schedules as $schedule)
	<tr>
		<td> {{ $schedule->id }} </td>
		<td> {{ $days[$schedule->day] }} </td>
		<td> {{ $schedule->start_time }} </td>
		<td> {{ $schedule->end_time }} </td>
		{{ Form::open(array('url'=>'member/schedule/edit')) }}
		{{ Form::hidden('id', $schedule->id) }}
		<td> {{ Form::submit('Edit') }} </td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'member/schedule/destroy')) }}
		{{ Form::hidden('id', $schedule->id) }}
		<td> {{ Form::submit('Delete') }} </td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
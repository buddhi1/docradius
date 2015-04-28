@extends('member.layouts.main')

@section('content')

<div>The delete requested schedule already has appoinments.</div>
<div>
	<h3>Schedule summary</h3>
	<div>Day: {{ $schedule->day }} </div>
	<div>Shedule duration: {{ $schedule->start_time.' to '. $schedule->end_time}} </div>
	<div>Maximum no of patients: {{ $schedule->no_of_patients }}</div>
	<div>Hospital: {{ $schedule->hospital }} </div>
	<div>No of appoinments: {{ $channels }}</div>
</div>
<P>Are you sure you want to delete the slected schedule? </P>
{{ Form::open(array('url'=>'member/schedule/deleteall')) }}
{{ Form::hidden('id', $schedule->id) }}
<div> {{ Form::submit('Yes') }} </div>
{{ Form::close() }}

{{ Form::open(array('url'=>'member/schedule/index', 'method'=>'GET')) }}
<div> {{ Form::submit('No') }} </div>
{{ Form::close() }}
@stop
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

{{ Form::open(array('url'=>'member/schedule/update')) }}
{{ Form::hidden('id', $schedule->id) }}
<div> {{ Form::label('', 'Select Day') }}: {{ Form::select('day', array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Firday', 'Saturday'), $schedule->day) }} </div>
<div> {{ Form::label('', 'Doctor') }}: {{ Form::label('doctor_id', $schedule->name) }} </div>
<div> {{ Form::label('', 'Maximum number of channels') }}: {{ Form::text('no_of_patients', $schedule->no_of_patients) }} </div>
<div> {{ Form::label('', 'Start time') }}: {{ Form::text('start_time', $schedule->start_time) }} </div>
<div> {{ Form::label('', 'End time') }}: {{ Form::text('end_time', $schedule->end_time) }} </div>
<div> {{ Form::submit('Save changes') }} </div>
{{ Form::close() }}


@stop
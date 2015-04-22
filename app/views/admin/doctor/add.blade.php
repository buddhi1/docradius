@extends('admin.layouts.main')

@section('content')
{{ Form::open(array('url'=>'admin/doctor/create', )) }}

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

<div>{{ Form::label('lblname', 'Doctor name') }}: {{ Form::text('name') }}</div>
<div>{{ Form::label('lblname', 'Description') }}: {{ Form::textarea('description') }}</div>
<div>{{ Form::label('lblname', 'Experience') }}: {{ Form::textarea('experience') }}</div>
<div>{{ Form::label('lblname', 'Contact No.') }}: {{ Form::text('tp') }}</div>
<div>{{ Form::label('lblname', 'Special message') }}: {{ Form::textarea('special') }}</div>
<div>{{ Form::label('lblname', 'Email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lblname', 'Passowrd') }}: {{ Form::password('password') }}</div>
<div>{{ Form::submit('Add doctor') }}</div>

{{ Form::close() }}
@stop
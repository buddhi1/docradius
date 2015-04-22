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

{{ Form::open(array('url'=>'admin/specialty/create')) }}
<div> {{ Form::label('lblname', 'Specilty') }}: {{ Form::text('name') }} </div>
<div> {{ Form::submit('Add new Specialty') }} </div>
{{ Form::close() }}
@stop
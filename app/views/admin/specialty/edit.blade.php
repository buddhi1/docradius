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

{{ Form::open(array('url'=>'admin/specialty/update')) }}
{{ Form::hidden('id', $specialty->id) }}
<div> {{ Form::label('lblname', 'Specilty') }}: {{ Form::text('name', $specialty->name) }} </div>
<div> {{ Form::submit('Save Changes') }} </div>
{{ Form::close() }}
@stop
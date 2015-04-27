@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))
	<div>{{ Session::get('message') }}</div>
@endif
@if($errors->has())
	<div class="alert alert-danger">
		<ul>
			@foreach($errors->all() as $error)			
					<li> {{ $error }} </li>			
			@endforeach
		</ul>
	</div>
@endif

{{ Form::open(array('url'=>'member/job/create')) }}

<div>{{ Form::label('title', 'Title') }}: {{ Form::text('title', null, array('required')) }}</div>
@if(Auth::check())
	@if(Auth::user()->type === 1)
	<div>{{ Form::label('email', 'Email') }}: {{ Form::text('email', null) }}</div>
	@endif
@endif
<div>{{ Form::label('des', 'Description') }}: {{ Form::textarea('des', null, array('required')) }}</div>
<div> {{ Form::submit('Create') }} </div>

{{ Form::close() }}
@stop
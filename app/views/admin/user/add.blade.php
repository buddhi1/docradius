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

{{ Form::open(array('url'=>'admin/user/create')) }}

<div>{{ Form::label('lblemail', 'User email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lblpassword', 'User password') }}: {{ Form::password('password') }}</div>
<div> {{ Form::submit('Add new user') }} </div>

{{ Form::close() }}
@stop
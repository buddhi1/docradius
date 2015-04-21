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

{{ Form::open(array('url'=>'admin/user/update')) }}
{{ Form::hidden('id', $user->id) }}
<div>{{ Form::label('lblemail', 'User email') }}: {{ Form::email('email', $user->email) }}</div>
<div>{{ Form::label('lblpassword', 'User password') }}: {{ Form::password('password') }}</div>
<div> {{ Form::submit('Save changes') }} </div>

{{ Form::close() }}
@stop
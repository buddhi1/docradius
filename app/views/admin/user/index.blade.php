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

<table border="1">
	<tr>
		<th>User ID</th>
		<th>User Email</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	@foreach($users as $user)
	<tr>
		<td> {{ $user->id }} </td>
		<td> {{ $user->email }} </td>
		{{ Form::open(array('url'=>'admin/user/edit')) }}
		{{ Form::hidden('id', $user->id) }}
		<td> {{ form::submit('Edit') }} </td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/user/destroy')) }}
		{{ Form::hidden('id', $user->id) }}
		<td> {{ form::submit('Delete') }} </td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
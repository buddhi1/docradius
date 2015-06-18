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

<table border="1">
	<tr>
		<th>Hospital ID</th>
		<th>Hospital name</th>
		<th>Account status</th>
		<th>Address</th>	
		<th>Email</th>
		<th colspan="2">Edit/Delete</th>
	</tr>
	@foreach($hospitals as $hospital)
	<tr>
		<td>{{$hospital->id}}</td>
		<td>{{$hospital->name}}</td>
		<td>
			@if($hospital->active == 1)
				{{'Active'}}
			@else
				{{'In-active'}}
			@endif
		</td>
		<td>{{$hospital->address.', '.$hospital->town}}</td>
		<td>{{$hospital->email}}</td>
		{{ Form::open(array('url'=>'admin/hospital/edit')) }}
		{{ Form::hidden('id', $hospital->id) }}
		<td>{{ Form::submit('Edit') }}</td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/hospital/destroy')) }}
		{{ Form::hidden('id', $hospital->id) }}
		<td>{{ Form::submit('Delete') }}</td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
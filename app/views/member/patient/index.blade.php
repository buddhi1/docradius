@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))

	<div class="alert alert-danger">{{ Session::get('message') }}</div>

@endif

<table border = "1">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Contact No.</th>
		<th>Gender</th>
		<th>Town</th>
		<th>Edit Profile</th>
		<th>Edit Account Settings</th>
		<th>Delete</th>
	</tr>

@foreach($patients as $patient)
	<tr>
		<td>{{ $patient->name }}</td>
		<td>{{ $patient->email }}</td>
		<td>{{ $patient->tp }}</td>

		@if($patient->sex == '0')
			<td>Male</td>
		@elseif($patient->sex == '1')
			<td>Female</td>
		@endif
		<td>{{ $patient->town_id }}</td>
		<td>
			{{ Form::open(array('url'=>'member/patient/editprofile')) }}

				{{ Form::hidden('p_id', $patient->id) }}
				{{ Form::submit('Edit Profile', array('class'=>'btn btn-info')) }}

			{{ Form::close() }}
		</td>
		<td>
			{{ Form::open(array('url'=>'member/patient/editaccount')) }}

				{{ Form::hidden('a_id', $patient->id) }}
				{{ Form::submit('Edit Account', array('class'=>'btn btn-info')) }}

			{{ Form::close() }}
		</td>
		<td>
			{{ Form::open(array('url'=>'member/patient/destroy')) }}

				{{ Form::hidden('id', $patient->id) }}
				{{ Form::submit('Delete', array('class'=>'btn btn-danger')) }}

			{{ Form::close() }}
		</td>
	</tr>
@endforeach
	
</table>

@stop
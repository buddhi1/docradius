@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))

	<div class="alert alert-danger">{{ Session::get('message') }}</div>

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

<table border = "1">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Contact No.</th>
		<th>Gender</th>
		<th>Town</th>
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
			{{ Form::open(array('url'=>'member/patient/destroy')) }}

				{{ Form::hidden('id', $patient->id) }}
				{{ Form::submit('Delete', array('class'=>'btn btn-danger')) }}

			{{ Form::close() }}
		</td>
	</tr>
@endforeach
	
</table>

@stop
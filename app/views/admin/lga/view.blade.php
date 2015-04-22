@extends('admin.layouts.main')

@section('content')

	<h3>Add a New Lga</h3>

	{{ Form::open(array('url' => 'admin/lga/create')) }}

		{{ Form::label('lga_name', 'LGA Name') }}
		{{ Form::text('name',null, array('required')) }}
		<br>
		{{ Form::label('state', 'LGA') }}
		{{ Form::Select('state', $lgas) }}
		<br>
		{{ Form::label('town', 'State') }}
		{{ Form::Select('town', $towns) }}
		<br>
		{{ Form::submit('Submit') }}

	{{ Form::close()}}

<!-- Display all the states -->

	<h3>All LGAs</h3>

	<table border = "1">
		<th>LGA</th>
		<th>State</th>
		<th>Delete</th>

		@foreach($lgas as $lga)

			<tr>
				<td>{{ $lga->name }}</td>
				<td>{{ $lga->state_id }}</td>
				<td>
					{{ Form::open(array('url' => 'admin/lga/destroy')) }}

						{{ Form::hidden('id', $lga->id) }}
						{{ Form::submit('Delete') }}

					{{ Form::close() }}
				</td>
			</tr>

		@endforeach

	</table>

	@if(Session::has('message'))

		<p>{{ Session::get('message') }}</p>

	@endif

	@if($errors->has())
		<p> The following errors has occurred:</p>

		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
		<!--end form-errors-->
	@endif

<!--end of display -->
@stop
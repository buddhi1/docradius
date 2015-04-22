@extends('admin.layouts.main')

@section('content')

	<h3>Add a New State</h3>

	{{ Form::open(array('url' => 'admin/state/create')) }}

		{{ Form::label('state_name', 'State Name') }}
		{{ Form::text('name',null, array('required')) }}
		{{ Form::submit('Submit') }}

	{{ Form::close()}}

<!-- Display all the states -->

	<h3>All States</h3>

	<table border = "1">
		<th>State Name</th>
		<th>Delete</th>

		@foreach($states as $state)

			<tr>
				<td>{{ $state->name }}</td>
				<td>
					{{ Form::open(array('url' => 'admin/state/destroy')) }}

						{{ Form::hidden('id', $state->id) }}
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
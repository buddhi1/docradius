@extends('admin.layouts.main')

@section('content')

<h2>Towns</h2>
<table border = "1">
	@foreach($all_towns as $town)
		<tr>
			<td>{{ $town }}</td>
		</tr>
	@endforeach
</table>

<h2>LGAs</h2>
<table border = "1">
	@foreach($all_lgas as $lga)
		<tr>
			<td>{{ $lga }}</td>
		</tr>
	@endforeach
</table>

<p>
	When you delete this state all these LGAs and Towns will be deleted. Are you sure you want to continue?
</p>

{{ Form::open(array('url'=>'admin/state/destroyall')) }}
{{ Form::hidden('id', $state_id) }}
{{ Form::submit('Yes') }}
{{ Form::close() }}
{{ Form::open(array('url'=>'admin/state/index', 'method'=>'GET')) }}
{{ Form::submit('No') }}
{{ Form::close() }}

@stop
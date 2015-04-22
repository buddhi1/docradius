@extends('admin.layouts.main')

@section('content')

<table border = "1">
	@foreach($towns as $town)
		<tr>
			<td>{{ $town->name }}</td>
		</tr>
	@endforeach
</table>

<p>
	When you delete this state all these states will be deleted. Are you sure you want to continue?
</p>

{{ Form::open(array('url'=>'admin/lga/destroyall')) }}
{{ Form::hidden('id', $towns[0]->lga_id) }}
{{ Form::submit('Yes') }}
{{ Form::close() }}
{{ Form::open(array('url'=>'admin/lga/index', 'method'=>'GET')) }}
{{ Form::submit('No') }}
{{ Form::close() }}

@stop
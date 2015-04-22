@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))
	<div> {{ Session::get('message') }} </div>
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
		<th>Specilty ID</th>
		<th>Specialty Name</th>
		<th>Delete</th>
	</tr>
	@foreach($specialties as $specialty)
		<tr>
			<td> {{ $specialty->id }} </td>
			<td> {{ $specialty->name }} </td>							
			{{ Form::open(array('url'=>'admin/specialty/destroy')) }}
			{{ Form::hidden('id', $specialty->id) }}
			<td> {{ Form::submit('Delete') }} </td>
			{{ Form::close() }}				
		</tr>
	@endforeach
</table>
@stop
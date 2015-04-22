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
		<th>Advertisement ID</th>
		<th>Advertisement description</th>
		<th>State</th>
		@if(Session::has('type'))
			@if(Session::get(type) == 1)
				<th colspan="2">View/Delete</th>
			@endif
		@else
			<th colspan="2">View</th>
		@endif
	</tr>
	@foreach($adverts as $advert)
	<tr>
		<td> {{ $advert->id }} </td>
		<td> {{ substr($advert->description, 0, 10) }}... </td>
		
		@if(Session::has('type'))
			{{ Form::open(array('url'=>'admin/advert/update')) }}
			{{ Form::hidden('id', $advert->id) }}
			@if(Session::get(type) == 1)
				@if($advert->active == 1)
					{{ Form::hidden('state', 1) }}
					<td>Active {{Form::submit('Deactivate advert')}} </td>
				@else
					{{ Form::hidden('state', 0) }}
					<td>Deactivate {{Form::submit('Activate advert')}} </td>
				@endif
			@endif
			{{ Form::close() }}
		@else
			@if($advert->active == 1)
				<td>Active</td>
			@else
				<td>Deactivate</td>
			@endif
		@endif
		
		{{ Form::open(array('url'=>'admin/advert/show')) }}
		{{ Form::hidden('id', $advert->id) }}
		<td> {{ Form::submit('View') }} </td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/advert/destroy')) }}
		{{ Form::hidden('id', $advert->id) }}
		<td> {{ Form::submit('Delete') }} </td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
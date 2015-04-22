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
		<th>Advertisement Image</th>
		<th>Advertisement Link</th>		
		<th colspan="2">Edit/Delete</th>		
	</tr>
	@foreach($adverts as $advert)
	<tr>
		<td> {{ $advert->id }} </td>
		<td> <img src="{{url()}}/uploads/adverts/{{$advert->image}}" height="60" /></td>
		{{ Form::open(array('url'=>'admin/advert/edit')) }}
		{{ Form::hidden('id', $advert->id) }}
		<td> {{ $advert->link }} </td>
		<td> {{ Form::submit('Edit') }} </td>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'admin/advert/destroy')) }}
		{{ Form::hidden('id', $advert->id) }}
		<td> {{ Form::submit('Delete') }} </td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>

@stop
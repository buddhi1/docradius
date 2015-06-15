@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))
	<div>{{ Session::get('message') }}</div>
@endif
<table border = "1">
	<tr>
		<th>Title</th>
		<th>Description</th>
		<th>Active</th>
		<th>Delete</th>
	</tr>

@foreach($jobs as $job)
	<tr>
		<td>{{ $job->title }}</td>
		<td>{{ $job->description }}</td>
		<td>			
			{{ Form::open(array('url'=>'member/job/makejobactive')) }}

				{{ Form::hidden('id', $job->id) }}

				@if($job->active === 0)

					{{ Form::label('Not Active', null, array('id' => 'active'))}}
					@if(Auth::user()->type == 1)
						{{ Form::submit('Activate', array('class'=>'btn btn-danger')) }}
					@endif
				@else
					{{ Form::label('Active', null, array('id' => 'active'))}}
					@if(Auth::user()->type == 1)
						{{ Form::submit('Deactivate', array('class'=>'btn btn-danger')) }}
					@endif
				@endif

			{{ Form::close() }}
		</td>
		<td>
			{{ Form::open(array('url'=>'member/job/destroy')) }}

				{{ Form::hidden('id', $job->id) }}
				{{ Form::submit('Delete', array('class'=>'btn btn-danger')) }}

			{{ Form::close() }}
		</td>
	</tr>
@endforeach
	
</table>

@stop
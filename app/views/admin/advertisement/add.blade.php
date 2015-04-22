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

{{ Form::open(array('url'=>'admin/advert/create')) }}
<div> {{ Form::label('lbllink', 'Advertisement link') }}: {{ Form::text('link') }} </div>
<div>
	{{ Form::file('Select Image') }}
</div>
<div></div>
<div> {{ Form::submit('Add advertisement') }} </div>
{{ Form::close() }}
@stop
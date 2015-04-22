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

{{ Form::open(array('url'=>'admin/advert/index', 'method'=>'GET')) }}
<div> {{ Form::label('lbldescription', 'Advertisement ID') }}: {{ Form::label('lbldescription', $advert->id) }} </div>
<div> {{ Form::label('lbldescription', 'Description') }}: {{ Form::label('lbldescription', $advert->description) }} </div>
<div> {{ Form::submit('Back') }} </div>
{{ Form::close() }}
@stop
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

{{ Form::open(array('url'=>'admin/advert/create', 'files'=>true)) }}
<div> {{ Form::label('lbllink', 'Advertisement link') }}: {{ Form::text('link') }} </div>
<div>
	{{ Form::file('files', array('id'=>'advert_img', 'accept'=>'image/jpeg')) }}
</div>
{{ Form::hidden('image_data','', array('id'=>'image_data')) }}
<div id="displayArea2"></div>
<div> {{ Form::submit('Add advertisement') }} </div>
{{ Form::close() }}

<script type="text/javascript" src="{{ url() }}/js/admin/photos.js"></script>
<script type="text/javascript" src="{{ url() }}/js/admin/image.js"></script>
@stop
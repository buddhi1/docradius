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

{{ Form::open(array('url'=>'member/doctor/create', 'files'=>true)) }}

<div>{{ Form::label('lblname', 'Doctor name') }}: {{ Form::text('name') }}</div>
<div>
	{{ Form::select('') }}
</div>
<div>{{ Form::label('lblname', 'Description') }}: {{ Form::textarea('description') }}</div>
<div>{{ Form::label('lblname', 'Experience') }}: {{ Form::textarea('experience') }}</div>
<div>{{ Form::label('lblname', 'Contact No.') }}: {{ Form::text('tp') }}</div>
<div>{{ Form::label('lblname', 'Special message') }}: {{ Form::textarea('special') }}</div>
<div>{{ Form::label('lblname', 'Email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lblname', 'Passowrd') }}: {{ Form::password('password') }}</div>
<div>
{{ Form::label('lblimage', 'Profile picture') }}: {{ Form::file('files', array('id'=>'advert_img', 'accept'=>'image/jpeg')) }}
</div>
{{ Form::hidden('image_data','', array('id'=>'image_data')) }}
<div id="displayArea2"></div>
<div>{{ Form::submit('Add doctor') }}</div>

{{ Form::close() }}

<script type="text/javascript" src="{{ url() }}/js/admin/photos.js"></script>
<script type="text/javascript" src="{{ url() }}/js/admin/image.js"></script>
@stop
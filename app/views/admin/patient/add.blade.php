@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))
	<div>{{ Session::get('message') }}</div>
@endif
@if($errors->has())
	<div class="alert alert-danger">
	<ul>
		@foreach($errors->all() as $error)			
				<li> {{ $error }} </li>			
		@endforeach
	</ul>
</div>
@endif

{{ Form::open(array('url'=>'admin/patient/create')) }}

<div>{{ Form::label('lblName', 'Name') }}: {{ Form::text('name') }}</div>
<div>{{ Form::label('lblemail', 'Email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lblpassword', 'Password') }}: {{ Form::password('password') }}</div>
<div>{{ Form::label('lbltp', 'Telephone No.') }}: {{ Form::text('tp') }}</div>
<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states) }}{{ Form::select('lga', $lgas) }}{{ Form::select('town', $towns) }}</div>
<div> {{ Form::submit('Create') }} </div>

{{ Form::close() }}
@stop
<html>
<head>
	<title>Docradius - Edit account</title>
</head>

<body>
<h1>Docradius - Account</h1>

<div>
	{{-- Form::open(array('url'=>'admin/doctor')) --}}
	{{ Form::hidden('id', $user->id) }}
	@if($type != 1)
	<div> {{ Form::label('', 'Current password') }}: {{ Form::password('password') }} </div>
	@endif
	<div>{{ Form::label('', 'Email') }}: {{ Form::text('email', $user->email, array('id'=>email)) }}</div>
	<div> {{ Form::label('', 'New password') }}: {{ Form::password('np', array('id'=>np)) }} </div>
	@if($type != 1)
	<div> {{ Form::label('', 'Confirm password') }}: {{ Form::password('cp', array('id'=>cp)) }} </div>
	@endif
	<div> {{ Form::submit('Save changes') }} </div>
</div>

</body>
</html>
<html>
<head>
	<title>Docradius - Edit account</title>
</head>

<body>
<h1>Docradius - Account</h1>

<div>
	{{ Form::hidden('id', $user->id) }}
	@if($type != 1)
	<div> {{ Form::label('', 'Current password') }}: {{ Form::password('password') }} </div>
	@endif
	<div>{{ Form::label('', 'Email') }}: {{ Form::text('email', $user->email) }}</div>
	<div> {{ Form::label('', 'New password') }}: {{ Form::password('np') }} </div>
	@if($type != 1)
	<div> {{ Form::label('', 'Confirm password') }}: {{ Form::password('cp') }} </div>
	@endif
	<div> {{ Form::submit('Save changes') }} </div>
</div>

</body>
</html>
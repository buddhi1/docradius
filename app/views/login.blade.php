<html>
<head>
	<title>Docradius - Login</title>
</head>

<body>
<h1>Docradius</h1>

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

<div>
	{{ Form::open(array('url'=>'member/login')) }}
	<div> {{ Form::text('email', '', array('placeholder'=> 'Email here')) }} </div>
	<div> {{ Form::password('password', array('placeholder'=>'Password here')) }} </div>
	<div> {{ Form::submit('Login') }} </div>
	{{ Form::close() }}
</div>

</body>
</html>
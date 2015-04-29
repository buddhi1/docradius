<html>
<head>
	<title>Docradius - Member Panel</title>
</head>

<body>
<h1>Docradius - Member area</h1>
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
	@yield('content')
</div>

</body>
</html>
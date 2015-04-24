<html>
<head>
	<title>Docradius - Edit account</title>
</head>

<body>
<h1>Docradius - Account</h1>

<div>
	{{ Form::open(array('url'=>'admin/user/updateaccountsettings'))}}
	{{ Form::hidden('id', $user->id) }}
	@if($type != 1)
	<div> {{ Form::label('', 'Current password') }}: {{ Form::password('password') }} </div>
	@endif
	<div>{{ Form::label('', 'Email') }}: {{ Form::text('email', $user->email) }}</div>
	<div> {{ Form::label('', 'New password') }}: {{ Form::password('np', array('id' => 'pw')) }} </div>
	@if($type != 1)
	<div> {{ Form::label('', 'Confirm password') }}: {{ Form::password('cp', array('id'=>'confirm')) }} </div>
	@endif
	<div> {{ Form::submit('Save changes', array('id' => 'submit')) }} </div>
	{{ Form::close() }}
</div>
<div id = "ok">Password Matched</div>
<div id = "wrong">Password is Incorrect</div>
<script type="text/javascript">
	document.getElementById('ok').style.display =  "none";
	document.getElementById('wrong').style.display =  "none";
	document.getElementById('confirm').onkeyup = function(){	
		
		var pw = document.getElementById('pw').value;
		var confirm = document.getElementById('confirm').value;
		
		document.getElementById('ok').style.display =  "none";
		document.getElementById('wrong').style.display =  "block";
		document.getElementById('submit').disabled = true;
		if(pw == confirm){
					
			document.getElementById('submit').disabled = false;
			document.getElementById('ok').style.display =  "block";
			document.getElementById('wrong').style.display =  "none";
		}
	}
	
</script>
</body>
</html>
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

{{ Form::open(array('url'=>'admin/hospital/create')) }}
	<div>{{ Form::label('', 'Account state') }}: {{ Form::checkbox('active') }} {{ Form::label('', 'Active') }}</div>
	<div>{{ Form::label('', 'Hospital name') }}: {{ Form::text('name') }}</div>
	<div>
		{{ Form::label('', 'Available Insurences') }}
		@foreach($insurances as $insurance)
			<div>
				{{ Form::checkbox('insurance[]', $insurance->name) }}
				{{ $insurance->name }}
			</div>
		@endforeach
	</div>
	<div>{{ Form::label('', 'Address') }}: {{ Form::text('address') }}</div>
	<div>{{ Form::label('', 'Street') }}: {{ Form::text('street') }}</div>
	<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, null, array('id' => 'state')) }}{{ Form::select('lga', array('default'=>'Select'), null, array('id' => 'lga')) }}{{ Form::select('town_id', array('default'=>'Select'), null, array('id' => 'town')) }}</div>
	<div>{{ Form::label('', 'Email') }}: {{Form::text('email')}}</div>
	<div>{{Form::label('', 'Password')}}: {{ Form::password('password') }}</div>
	<div>{{ Form::submit('Add hospital') }}</div>
{{ Form::close() }}


<script type="text/javascript">

window.onload = function() {

	document.getElementById("lga").style.visibility = "hidden";
	document.getElementById("town").style.visibility = "hidden";
	document.getElementById("town").value = ""
}

document.getElementById('state').onchange = function(){

	if(document.getElementById("state").value) {

		document.getElementById("lga").style.visibility = "visible";
		document.getElementById("town").style.visibility = "hidden";
		document.getElementById("town").value = "";
	    var state_id = document.getElementById('state').value;

	    var xmlHttp = new XMLHttpRequest(); 
	    xmlHttp.onreadystatechange = function(){

	        if (xmlHttp.readyState==4 && xmlHttp.status==200){

	            lgaDropDown(xmlHttp.responseText);
	        }
	    };
	    xmlHttp.open( "GET", 'dropdowns?state_id=' + state_id, true );
	    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	    xmlHttp.send();
	} else {

		document.getElementById("lga").style.visibility = "hidden";
		document.getElementById("town").style.visibility = "hidden";
		document.getElementById("lga").value = "";
		document.getElementById("town").value = "";
	}
}

document.getElementById('lga').onchange = function(){

	if(document.getElementById("lga").value) {
		document.getElementById("town").style.visibility = "visible";
	    var lga_id = document.getElementById('lga').value;

	    var xmlHttp = new XMLHttpRequest(); 
	    xmlHttp.onreadystatechange = function(){

	        if (xmlHttp.readyState==4 && xmlHttp.status==200){

	            townDropDown(xmlHttp.responseText);
	        }
	    };
	    xmlHttp.open( "GET", 'towndrop?lga_id=' + lga_id, true );
	    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	    xmlHttp.send();
	} else {

		document.getElementById("town").style.visibility = "hidden";
		document.getElementById("town").value = "";
	}
}

var DropDowns = function(para, name, des) {

	var drop = JSON.parse(para);
		
	document.getElementById(name).options.length=0;
	var option = document.createElement("option");
    option.text = des;
    option.value = '';
    var select = document.getElementById(name);
    select.appendChild(option);
	for(var i=0; i<drop.length;i++){
			
		var option = document.createElement("option");
		option.text = drop[i]['name'];
		option.value = drop[i]['id'];
		var select = document.getElementById(name);
		select.appendChild(option);
	}
}

var lgaDropDown = function(lga) {

	DropDowns(lga, 'lga', 'Select a LGA');
}

var townDropDown = function(town) {

	DropDowns(town, 'town', 'Select a Town');
}

</script>

@stop
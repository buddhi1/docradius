@extends('member.layouts.main')

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

{{ Form::open(array('url'=>'member/schedule/create')) }}
<div> {{ Form::label('', 'Select Day') }}: {{ Form::select('day', array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Firday', 'Saturday')) }} </div>
<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, null, array('id' => 'state')) }}{{ Form::select('lga', array('default'=>'Select'), null, array('id' => 'lga')) }}{{ Form::select('town_id', array('default'=>'Select'), null, array('id' => 'town')) }}</div>
<div> {{ Form::label('', 'Hospital') }}: {{ Form::text('hospital', '') }} </div>
<div> {{ Form::label('', 'Maximum number of channels') }}: {{ Form::text('no_of_patients') }} </div>
<div> {{ Form::label('', 'Start time') }}: {{ Form::text('start_time', '') }} </div>
<div> {{ Form::label('', 'End time') }}: {{ Form::text('end_time', '') }} </div>
<div> {{ Form::submit('Add schedule') }} </div>
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
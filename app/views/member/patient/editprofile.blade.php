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

{{ Form::open(array('url'=>'member/patient/updateprofile')) }}

	{{ Form::hidden('id', $patient->id)}}
<div>{{ Form::label('lblName', 'Name') }}: {{ Form::text('name' ,$patient->name) }}</div>
<div>{{ Form::label('lblGender', 'Gender') }}:
@if ($patient->sex == 0)
	{{ Form::radio('sex', '0', true) }} Male {{ Form::radio('sex', '1') }} Female
@elseif ($patient->sex == 1)
	{{ Form::radio('sex', '0') }} Male {{ Form::radio('sex', '1', true) }} Female
@endif
<div>{{ Form::label('lbltp', 'Telephone No.') }}: {{ Form::text('tp' ,$patient->tp) }}</div>
<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, $state_id, array('id' => 'state')) }}{{ Form::select('lga', $lgas, $lga_id, array('id' => 'lga')) }}{{ Form::select('town_id', $towns, $town_id, array('id' => 'town')) }}</div>
<div> {{ Form::submit('Edit') }} </div>

{{ Form::close() }}

<script type="text/javascript">

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
	var e = document.getElementById("lga").selected.value = <?php
		echo $lga_id;
	?>;
}

var townDropDown = function(town) {

	DropDowns(town, 'town', 'Select a Town');
}
</script>
@stop
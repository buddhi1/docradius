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


{{ Form::open(array('url'=>'admin/hospital/update')) }}
{{ Form::hidden('id', $hospital->id) }}
	<div>{{ Form::label('', 'Account state') }}:
		@if($hospital->active ==1)
		 	{{ Form::checkbox('active', '', array('checked')) }} 

		@else
			 {{ Form::checkbox('active') }} 
		@endif
		 {{ Form::label('', 'Active') }}
	</div>
	<div>{{ Form::label('', 'Hospital name') }}: {{ Form::text('name', $hospital->name) }}</div>
	<div>
		{{ Form::label('', 'Available Insurences') }}
		@foreach($insurances as $insurance)
			<div>
				<input type = "checkbox" name = "insurance[]" value = "<?php echo $insurance['name']; ?>" <?php
						if(in_array($insurance->name, json_decode($hospital->insurances,true))) {
							echo "checked";
						}
					?>>
				{{ $insurance->name }}
			</div>
		@endforeach
	</div> 
	<div>{{ Form::label('', 'Address') }}: {{ Form::text('address', explode(', ', $hospital->address)[0]) }}</div>
	<div>{{ Form::label('', 'Street') }}: {{ Form::text('street', explode(', ', $hospital->address)[1]) }}</div>
	<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, $state, array('id' => 'state')) }}{{ Form::select('lga', $lga_sel, $lga, array('id' => 'lga')) }}{{ Form::select('town_id',  $town_sel, $hospital->town_id, array('id' => 'town')) }}</div>
	<div>{{ Form::label('', 'Email') }}: {{Form::text('email', $user->email)}}</div>
	<div>{{Form::label('', 'Password')}}: {{ Form::password('password') }}</div>
	<div>{{ Form::submit('Save changes') }}</div>
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
}

var townDropDown = function(town) {

	DropDowns(town, 'town', 'Select a Town');
}

</script>

@stop
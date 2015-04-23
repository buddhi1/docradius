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

{{ Form::open(array('url'=>'member/patient/create')) }}

<div>{{ Form::label('lblName', 'Name') }}: {{ Form::text('name') }}</div>
<div>{{ Form::label('lblemail', 'Email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lbltp', 'Telephone No.') }}: {{ Form::text('tp') }}</div>
<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, null, array('id' => 'state')) }}{{ Form::select('lga', array('default'=>'Select'), null, array('id' => 'lga')) }}{{ Form::select('town_id', array('default'=>'Select'), null, array('id' => 'town')) }}</div>
<div> {{ Form::submit('Create') }} </div>

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

// var sendRequestToServer = function(headers, parameter) {

// 	var xmlHttp = new XMLHttpRequest(); 
//     xmlHttp.onreadystatechange = function(){

//         if (xmlHttp.readyState==4 && xmlHttp.status==200){

//             console.log(xmlHttp.responseText);
//         }
//     };
//     xmlHttp.open( "GET", headers + parameter, true );
//     xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
//     xmlHttp.send();
// }

// document.getElementById('state').onchange = function(){

// 	if(document.getElementById("state").value) {

// 		document.getElementById("lga").style.visibility = "visible";
// 		document.getElementById("town").style.visibility = "hidden";
// 		document.getElementById("town").value = "";
// 	    var state_id = document.getElementById('state').value;

// 	    sendRequestToServer('dropdowns?state_id=', state_id);
// 	} else {

// 		document.getElementById("lga").style.visibility = "hidden";
// 		document.getElementById("town").style.visibility = "hidden";
// 		document.getElementById("lga").value = "";
// 		document.getElementById("town").value = "";
// 	}
// }
</script>
@stop
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

{{ Form::open(array('url'=>'admin/patient/create')) }}

<div>{{ Form::label('lblName', 'Name') }}: {{ Form::text('name') }}</div>
<div>{{ Form::label('lblemail', 'Email') }}: {{ Form::email('email') }}</div>
<div>{{ Form::label('lblpassword', 'Password') }}: {{ Form::password('password') }}</div>
<div>{{ Form::label('lbltp', 'Telephone No.') }}: {{ Form::text('tp') }}</div>
<div>{{ Form::label('lbltown', 'Town') }}: {{ Form::select('state', $states, null, array('id' => 'state')) }}{{ Form::select('lga', array('default'=>'Select'), null, array('id' => 'lga')) }}{{ Form::select('town', array('default'=>'Select'), null, array('id' => 'town')) }}</div>
<div> {{ Form::submit('Create') }} </div>

{{ Form::close() }}

<script type="text/javascript">

window.onload = function() {

	document.getElementById("lga").style.visibility = "hidden";
	document.getElementById("town").style.visibility = "hidden";
}

document.getElementById('state').onchange = function(){
	document.getElementById("lga").style.visibility = "visible";
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
}

document.getElementById('lga').onchange = function(){
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
}

var lgaDropDown = function(lga) {

	var lgas = JSON.parse(lga);
		
	document.getElementById("lga").options.length=0;
	for(var i=0; lgas.length;++i){
		
		var option = document.createElement("option");
		option.text = lgas[i]['name'];
		option.value = lgas[i]['id'];
		var select = document.getElementById("lga");
		select.appendChild(option);
	}
}

var townDropDown = function(town) {

	var towns = JSON.parse(town);
		
	document.getElementById("town").options.length=0;
	for(var i=0; towns.length;++i){
		
		var option = document.createElement("option");
		option.text = towns[i]['name'];
		option.value = towns[i]['id'];
		var select = document.getElementById("town");
		select.appendChild(option);
	}
}
</script>
@stop
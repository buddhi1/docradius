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

{{ Form::open(array('url'=>'member/doctor/update', 'files'=>true)) }}
<div>
{{ Form::label('lblname', 'Verify Account') }}: 
@if($doctor->active == 1)
{{ Form::checkbox('active','1', array('checked')) }}
@else
{{Form::checkbox('active')}}
@endif
</div>

{{ Form::hidden('id', $doctor->id) }}
<div>{{ Form::label('lblname', 'Doctor name') }}: {{ Form::text('name', $doctor->name) }}</div>
<div>{{ Form::label('', 'Specialty') }}:</div>
<div id="selected_sp"></div>
<div>
	{{ Form::select('specialties_all', $specialties, null, array('id'=>'specialties_all')) }}
	{{ Form::button('Add specialty', array('id'=>'add_sp')) }}
</div>
{{ Form::hidden('specialties','', array('id'=>'specialties')) }}
<div>{{ Form::label('lbldesc', 'Description') }}: {{ Form::textarea('description', $doctor->description) }}</div>
<div>{{ Form::label('lblex', 'Experience') }}: {{ Form::textarea('experience',  $doctor->experience) }}</div>
<div>{{ Form::label('lblcno', 'Contact No.') }}: {{ Form::text('tp',  $doctor->tp) }}</div>
<div>{{ Form::label('lblspmessage', 'Special message') }}: {{ Form::textarea('special_popup',  $doctor->special_popup) }}</div>

<div>
{{ Form::label('lblimage', 'Profile picture') }}: {{ Form::file('files', array('id'=>'advert_img', 'accept'=>'image/jpeg')) }}
</div>
{{ Form::hidden('image_data','', array('id'=>'image_data')) }}
<div id="displayArea2"></div>
<div>{{ Form::submit('Save changes') }}</div>

{{ Form::close() }}

<script type="text/javascript">
var sp	= {{ $doctor->specialties }};

	window.onload = function(){
		var img_thumb = document.createElement("IMG");
		img_thumb.src = "{{ URL::to('/')."/uploads/profile_pictures/".$doctor->profile_picture }}";
		img_thumb.height = 200;
		document.getElementById("displayArea2").appendChild(img_thumb);
		loadSp();
	}

	document.getElementById('add_sp').onclick = function(){
		addSpecialty();
		document.getElementById('specialties_all').disabled = false;
		document.getElementById('add_sp').disabled = true;
	}
	document.getElementById('specialties_all').onchange = function(){	
		document.getElementById('specialties_all').disabled = true;
		document.getElementById('add_sp').disabled = false;
	}

	
	var addSpecialty = function(){			
					
		sp[sp.length] = document.getElementById('specialties_all').options[document.getElementById('specialties_all').selectedIndex].text;			
		document.getElementById("selected_sp").innerHTML = "";
		for (var i = sp.length - 1; i >= 0; i--) {
			var spDiv = document.createElement("DIV");
			spDiv.id = "div"+i;
			var spClose = document.createElement("DIV");
			spClose.id = i;
			var click = document.createAttribute("onclick");
			click.value = "removeSp("+i+")";
			spClose.setAttributeNode(click);
			
			var remove = document.createTextNode("Remove");
			var node = document.createTextNode(sp[i])
			spClose.appendChild(remove);
			spDiv.appendChild(spClose);
			spDiv.appendChild(node);
			var element = document.getElementById("selected_sp");
			element.appendChild(spDiv);
		};		

		document.getElementById('specialties').value = sp;	
	}

	var removeSp = function(x){
		document.getElementById("selected_sp").innerHTML = "";
		sp.splice(x, 1);
		document.getElementById("specialties").value = sp;

		for (var i = sp.length - 1; i >= 0; i--) {
			var spDiv = document.createElement("DIV");
			spDiv.id = "div"+i;
			var spClose = document.createElement("DIV");
			spClose.id = i;
			var click = document.createAttribute("onclick");
			click.value = "removeSp("+i+")";
			spClose.setAttributeNode(click);
			
			var remove = document.createTextNode("Remove");
			var node = document.createTextNode(sp[i])
			spClose.appendChild(remove);
			spDiv.appendChild(spClose);
			spDiv.appendChild(node);
			var element = document.getElementById("selected_sp");
			element.appendChild(spDiv);
		};
		document.getElementById('add_sp').disabled = false;		

		return sp;
	}

	var loadSp = function(){

		document.getElementById("selected_sp").innerHTML = "";
		document.getElementById("specialties").value = sp;
		for (var i = sp.length - 1; i >= 0; i--) {
			var spDiv = document.createElement("DIV");
			spDiv.id = "div"+i;
			var spClose = document.createElement("DIV");
			spClose.id = i;
			var click = document.createAttribute("onclick");
			click.value = "removeSp("+i+")";
			spClose.setAttributeNode(click);
			
			var remove = document.createTextNode("Remove");
			var node = document.createTextNode(sp[i])
			spClose.appendChild(remove);
			spDiv.appendChild(spClose);
			spDiv.appendChild(node);
			var element = document.getElementById("selected_sp");
			element.appendChild(spDiv);
		};		
	}
</script>
<script type="text/javascript" src="{{ url() }}/js/admin/photos.js"></script>
<script type="text/javascript" src="{{ url() }}/js/admin/image.js"></script>
@stop
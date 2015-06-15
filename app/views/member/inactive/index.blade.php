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

<div>
<table border="1">
	<tr>
		<th>Date</th>
		<th>Time slot</th>
		<th>Hospital</th>
		<th>Change state</th>
	</tr>
	@foreach($inactives as $inactive)
	<tr>
		<td>{{ $inactive->date }}</td>
		<td> {{ $inactive->start_time.' to '.$inactive->end_time }} </td>
		<td> {{ $inactive->hospital }} </td>
		{{ Form::open(array('url'=>'member/calendar/destroy')) }}
		{{ Form::hidden('id', $inactive->id) }}
		<td> {{ Form::submit('Active slot') }} </td>
		{{ Form::close() }}
	</tr>
	@endforeach
</table>
</div>

{{ Form::open(array('url'=>'member/calendar/create')) }}
<div> {{ Form::text('date','', array('id'=>'date')) }} </div>
<div> {{ Form::select('schedule',array('default'=>'Select') ,null, array('id'=>'schedule')) }} </div>
<div> {{ Form::submit('Inactive') }} </div>
{{ Form::close() }}

<script type="text/javascript">
document.getElementById('date').onclick = function(){
	loadTimeSlot();
}

var loadTimeSlot = function(){
	var date = document.getElementById('date').value;
	var xmlHttp = new XMLHttpRequest(); 
    xmlHttp.onreadystatechange = function(){

        if (xmlHttp.readyState==4 && xmlHttp.status==200){

            slotsDropDown(xmlHttp.responseText);
        }
    };
    xmlHttp.open( "GET", '{{url()}}/member/calendar/scheduledropdown?date=' + date, true );
    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlHttp.send();
}

var slotsDropDown = function(schedules){
	DropDowns(schedules, 'schedule', 'Select a Schedule');
} 

var DropDowns = function(para, name, des) {

	var drop = JSON.parse(para);
		
	document.getElementById(name).options.length = 0;
	var option = document.createElement("option");
    option.text = des;
    option.value = '';
    var select = document.getElementById(name);
    select.appendChild(option);
	for(var i=0; i<drop.length;i++){
			
		var option = document.createElement("option");
		option.text = drop[i]['start_time']+" to "+drop[i]['end_time'];
		option.value = drop[i]['id'];
		var select = document.getElementById(name);
		select.appendChild(option);
	}
}
</script>
@stop
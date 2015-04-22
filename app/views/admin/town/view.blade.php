@extends('admin.layouts.main')

@section('content')

	<h3>Add a New Town</h3>

	{{ Form::open(array('url' => 'admin/town/create')) }}

		{{ Form::label('name', 'Town Name') }}
		{{ Form::text('name',null, array('required')) }}
		<br>
		{{ Form::label('state', 'State') }}
		{{ Form::Select('state', $states, array('id' => 'state')) }}
		<br>
		{{ Form::label('lga', 'LGA') }}
		{{ Form::Select('lga', $lgas, array('id' => 'lga')) }}
		<br>
		{{ Form::submit('Submit') }}

	{{ Form::close()}}

<!-- Display all the Towns -->

	<h3>All Towns</h3>

	<table border = "1">
		<th>Town</th>
		<th>LGA</th>
		<!-- <th>State</th> -->
		<th>Delete</th>

		@foreach($towns as $town)

			<tr>
				<td>{{ $town->name }}</td>
				<td>{{ $town->lga_id }}</td>
				<!-- <td>{{ $town->state_id }}</td> -->
				<td>
					{{ Form::open(array('url' => 'admin/town/destroy')) }}

						{{ Form::hidden('id', $town->id) }}
						{{ Form::submit('Delete') }}

					{{ Form::close() }}
				</td>
			</tr>

		@endforeach

	</table>

	@if(Session::has('message'))

		<p>{{ Session::get('message') }}</p>

	@endif

	@if($errors->has())
		<p> The following errors has occurred:</p>

		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
		<!--end form-errors-->
	@endif
<script type="text/javascript" src="{{ url() }}/js/admin/dropDowns.js"></script>

<script type="text/javascript">
	window.onload = function() {

		var state_id = document.getElementById('state').value;

		var xmlHttp = new XMLHttpRequest(); 
	    xmlHttp.onreadystatechange = function(){

	        if (xmlHttp.readyState==4 && xmlHttp.status==200){

	            lgaDropDown(xmlHttp.responseText);
	        }
	    };
	    xmlHttp.open( "GET", 'town/dropdowns?state_id=' + state_id, true );
	    xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	    xmlHttp.send();
	}
</script>
<!--end of display -->
@stop
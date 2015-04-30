<html>
	<body>

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

		<h2>Search a Doctor</h2>
		{{ Form::open(array('url'=>'channel/searchbyspeciality', 'method' => 'GET')) }}

		<div>{{ Form::label('practitioner', 'Practitioner') }}: {{ Form::select('practitioner', $specialty) }}</div>
		<div>{{ Form::label('location', 'Location') }}: {{ Form::text('location', null) }}</div>
		<div> {{ Form::submit('Search') }} </div>

		{{ Form::close() }}

	</body>
</html>
<html>
	<body>

		@if(Session::has('message'))
			<div>{{ Session::get('message') }}</div>
		@endif

		<h2>Doctors</h2>

		<table border = "1">
			<tr>
				<th>Doctor Name</th>
				<th>Hospital</th>
				<th>Town</th>
				<th>Get Schedule</th>
			</tr>
			@foreach($doctors as $doctor)
				<tr>
					<td>{{ $doctor->name }}</td>
					<td>{{ $doctor->hospital }}</td>
					<td>{{ $doctor->town_id }}</td>
					<td><a href="schedule/{{$doctor->doctor_id}}">Schedule</a></td>
				</tr>
			@endforeach
		</table>
	</body>
</html>
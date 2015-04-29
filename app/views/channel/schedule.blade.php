<html>
	<body>

		@if(Session::has('message'))
			<div>{{ Session::get('message') }}</div>
		@endif

		<h2>Schedule</h2>

		<table border = "1">
			<tr>
				<th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Wednesday</th>
				<th>Thursday</th>
				<th>Friday</th>
				<th>Saturday</th>
			</tr>
			<tr>
				<td>
					@if($days[0])
					<table border = "1">
						@foreach($days[0] as $sunday)
							<tr>
								<td>
									<a href="create/{{$sunday->id}}">{{ $sunday->start_time }} and {{ $sunday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
					@endif
				</td>
				<td>
					@if($days[1])
					<table border = "1">
						@foreach($days[1] as $monday)
							<tr>
								<td>
									<a href="create/{{$monday->id}}">{{ $monday->start_time }} and {{ $monday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
					@endif
				</td>
				<td>
					<table border = "1">
						@foreach($days[2] as $tuesday)
							<tr>
								<td>
									<a href="create/{{$tuesday->id}}">{{ $tuesday->start_time }} and {{ $tuesday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table border = "1">
						@foreach($days[3] as $wednesday)
							<tr>
								<td>
									<a href="create/{{$wednesday->id}}">{{ $wednesday->start_time }} and {{ $wednesday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table border = "1">
						@foreach($days[4] as $thursday)
							<tr>
								<td>
									<a href="create/{{$thursday->id}}">{{ $thursday->start_time }} and {{ $thursday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table border = "1">
						@foreach($days[5] as $friday)
							<tr>
								<td>
									<a href="create/{{$friday->id}}">{{ $friday->start_time }} and {{ $friday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
				</td>
				<td>
					<table border = "1">
						@foreach($days[6] as $saturday)
							<tr>
								<td>
									<a href="create/{{$saturday->id}}">{{ $saturday->start_time }} and {{ $saturday->end_time }}</a>
								</td>
							</tr>
						@endforeach
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
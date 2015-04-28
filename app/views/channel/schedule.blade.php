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
			@foreach($schedules as $schedule)
				<tr>
					<td>
						@if($schedule->day === '0')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '1')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '2')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '3')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '4')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '5')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
					<td>
						@if($schedule->day === '6')
							{{ $schedule->start_time }} and {{ $schedule->end_time }}
						@endif
					</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>
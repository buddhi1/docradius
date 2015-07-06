@extends('frontend.includes.header')

@section('controllers')
	<script type="text/javascript" src="/app/controllers/loginController.js"></script>
	<script type="text/javascript" src="/app/controllers/adminControllers.js"></script>
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="label label-default">Navigation</div>
			<ul>
				<li><a href="#administrators">administrators</a></li>
				<li><a href="#hospitals">Hospitals</a></li>
				<li><a href="#patients">Patients</a></li>
			</ul>			
		</div>
		<div class="col-md-8">
			<div ng-view></div>
		</div>
	</div>
</div>
@stop

@section('routes')
	/*<script type="text/javascript">*/
	$routeProvider.when("/", {
		templateUrl: "/app/components/admin/main.html",
		controller: "adminController",
		data: {
			auth: true,
		}
	});

	//administrator management
	$routeProvider.when("/administrators", {
		templateUrl: '/app/components/admin/administrator/list.html',
		controller: "adminController",
		data: {
			auth: true,
		}
	});

	$routeProvider.when("/administrators/add", {
		templateUrl: '/app/components/admin/administrator/add.html',
		controller: "adminController",
		data: {
			auth: true,
		}
	});

	$routeProvider.when('/administrators/:id/edit', {
		templateUrl: '/app/components/admin/administrator/view.html',
		controller: "adminController",
		data: {
			auth: true, 
			view: 'edit'
		}
	})
@stop
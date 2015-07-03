@extends('frontend.includes.header')

@section('controllers')
	<script type="text/javascript" src="/app/controllers/loginController.js"></script>
@stop

@section('content')
	<div ng-view></div>
@stop

@section('routes')
	/*<script type="text/javascript">*/
	$routeProvider.when("/", {
		templateUrl: "/app/components/admin/index.html",
		controller: "loginController",
		data: {
			auth: true,
		}
	});
@stop
@extends('frontend.includes.header')

@section('controllers')
	<script type="text/javascript" src="/app/controllers/loginController.js"></script>
	<script type="text/javascript" src="/app/controllers/adminControllers.js"></script>
@stop

@section('content')
<div ui-view></div>
@stop

@section('routes')
	/*<script type="text/javascript">*/
	$stateProvider.state("panel", {
		abstract: true,
		templateUrl: "/app/components/panel.html",
		resolve: ['authService', function(authService){
			authService.chackAuthState();
		}],
	});

	$stateProvider.state('panel.main',{
		url: '/',
		templateUrl: "/app/components/admin/main.html",
	});

	$stateProvider.state("panel.administrators",{
		abstract: true,
		url: '/administrators',
		templateUrl: '/app/components/admin/administrator/main.html',
		controller: "adminController",
	});

	$stateProvider.state('panel.administrators.listAdmins',{
		url: '',
		templateUrl: '/app/components/admin/administrator/list.html',
		controller: ['$scope', function($scope){
			$scope.getAdminList();
		}],
	});

	$stateProvider.state('panel.administrators.addAdmins',{
		url: '/add',
		templateUrl: '/app/components/admin/administrator/add.html',
	});

	$stateProvider.state('panel.administrators.editAdmins',{
		url: '/{id:int}/edit',
		templateUrl: '/app/components/admin/administrator/edit.html',
		resolve: {
			editId: ['$stateParams', function($stateParams){
          		return $stateParams.id;
      		}]
		},
		controller: ['$scope','editId', function($scope, editId){
			$scope.editAdmin.id = editId;
		}],
	})

	//administrator management

	// $stateProvider.state('administrator.add', {
	// 	url: "/administrators/add",
	// 	templateUrl: '/app/components/admin/administrator/add.html',
	// 	controller: "adminController",
	// 	data: {
	// 		auth: true,
	// 	}
	// });

	// $stateProvider.state('administrator.edit', {
	// 	url: '/administrators/:id/edit',
	// 	templateUrl: '/app/components/admin/administrator/view.html',
	// 	controller: "adminController",
	// 	data: {
	// 		auth: true, 
	// 		view: 'edit'
	// 	}
	// })
@stop
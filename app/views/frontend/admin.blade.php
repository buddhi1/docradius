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


	// ***************** admin manage panel routes ******************* //

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

	// ********************** locaction manage panel routes ************** //

	$stateProvider.state('panel.locations', {
		abstract: true,
		url: '/locations',
		template: '<ui-view/>',
		controller: 'locationController',
	})

	$stateProvider.state('panel.locations.states', {
		url: '?state&lga',
		templateUrl: '/app/components/admin/location/locations.html',
		controller: ['$scope', '$stateParams', function($scope, $stateParams){
			$scope.getStates();
			$scope.data.lga = undefined;
			if($stateParams.state !== undefined && !isNaN($stateParams.state)){
				$scope.getLgaByState($stateParams.state);
				$scope.data.towns = undefined
				if($stateParams.lga !== undefined && !isNaN($stateParams.lga)){
					$scope.getTownByLga($stateParams.lga);
				}
			}
		}],
	})


	// ********************** insurance manage panel routes ************** //
	$stateProvider.state('panel.insurance', {
		abstract: true,
		template: '<ui-view/>',
		url: '/insurance',
		controller: 'insuranceController',
	});

	$stateProvider.state('panel.insurance.manageInsurance', {
		url: '?insurance',
		templateUrl: '/app/components/admin/insurance/manage.html',
		controller: ['$scope', '$stateParams', function($scope, $stateParams){
			$scope.getInsurance();
			if( $stateParams.insurance !== undefined && !isNaN($stateParams.insurance) ){
				$scope.viewData.currentInsurance = $stateParams.insurance;
			}else{
				$scope.viewData.currentInsurance = undefined;
			}
		}],
	});


	// ********************** hospital manage panel routes ************** //
	$stateProvider.state('panel.hospital', {
		abstract: true,
		url: '/hospitals',
		templateUrl: '/app/components/admin/hospital/main.html',
		controller: 'hospitalsController',
	});

	$stateProvider.state('panel.hospital.listHosiptal',{
		url: '',
		templateUrl: '/app/components/admin/hospital/list.html',
		controller: ['$scope', '$stateParams', function( $scope, $stateParams){
			$scope.getHospitalList($stateParams);
		}],
	});

	$stateProvider.state('panel.hospital.addHospital',{
		url: '/add',
		templateUrl: '/app/components/admin/hospital/add.html',
		controller: ['$scope', '$stateParams', function( $scope, $stateParams){
			$scope.getAllHospitalInsuranceList();
			$scope.getAllHospitalStateList();
		}],
	});
@stop
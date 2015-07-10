// admin management controller
angular.module('docradius').controller('adminController',[ '$scope', '$http', '$state',  function($scope,$http,$state){
	$scope.data = {};

	//explicitly defining form objects
	$scope.forms = {};


	$scope.getAdminList = function(){
		$http.get('/drad/admin/user')
		.success(function(res){
			$scope.data.admins = res.data.users;
		})
		.error(function(res){
			console.log(res);
			alert('error');
		});
	}

	$scope.addAdmin = function(newAdmin){
		if($scope.forms.adminAdd.$valid){
			$http.post('/drad/admin/user', newAdmin)
			.success(function(res){
				//$location.path('/administrators');
				$state.go('panel.administrators.listAdmins');
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
		}
	}

	$scope.editAdmin = {id: '', email: '', password: '_filler'};
	//watch for changes in the edit admin id and load admin details
	$scope.$watch('editAdmin.id', function(newValue){
		if(!newValue || newValue === undefined || newValue == null) return;
		$http.get('drad/admin/user/'+newValue)
		.success(function(res){
			$scope.editAdmin.email = res.data.admin.email;
			$scope.editAdmin.password = '_filler';
		})
		.error(function(res){
			console.log(error);
			alert('error');
		});
	});

	$scope.updateAdmin = function(editAdmin){
		if($scope.forms.adminEdit.$invalid) return;

		var updateVars = editAdmin;
		if( editAdmin.password == '_filler' ) delete updateVars.password;
		console.log(updateVars);
		$http.put('drad/admin/user/'+editAdmin.id, updateVars)
			.success( function(res){
				console.log(res);
				$state.go('panel.administrators.listAdmins');
			})
			.error( function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteAdmin = function(userId){
		$http.delete('/drad/admin/user/' + userId)
			.success( function(res){
				console.log(res);
				$scope.getAdminList();
			})
			.error( function(res){
				console.log(res);
				alert('error');
			});
	}

}]);


//location management controller
angular.module('docradius').controller('locationController' ,[ '$scope', '$http', '$state', function($scope, $http, $state){

	$scope.data = {};

	$scope.forms = {};

	$scope.stateEditId = undefined;
	$scope.lgaEditId = undefined
	$scope.townEditId = undefined;

	$scope.currentState = undefined;
	$scope.currentLga = undefined;

	$scope.setCurrentState = function (state) {
		$state.go('panel.locations.states', {state: state, lga: undefined});
	}

	$scope.setCurrentLga = function (lga) {
		$state.go('panel.locations.states', {state: $scope.currentState, lga: lga});
	}

	$scope.compareId = function(id1, id2){
		if(id1 == id2){
			return true;
		}else{
			return false;
		}
	};

	//states
	$scope.getStates = function(){
		$http.get('/drad/state/')
			.success( function(res){
				$scope.data.states = res.data.states;
			})
			.error( function(res){
				console.log(res);
				alert('error')
			});
	}

	$scope.setStateEditId = function(id){
		$scope.stateEditId = id;
	}

	$scope.createState = function(stateName){
		var state = {name: stateName};
		$http.post('/drad/state', state)
			.success(function(res){
				console.log(res);
				$scope.getStates();
			})
			.error(function(res){
				console.log(res);
				alert(error);
			});
	}

	$scope.updateStateName = function(state){
		$http.put('/drad/state/'+state.id, state)
			.success(function(res){
				$scope.setStateEditId( undefined );
				$scope.getStates();
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteState = function(stateId){
		$http.delete('drad/state/'+stateId)
			.success(function(res){
				console.log(res);
				$scope.stateEditId = undefined;
				$scope.getStates();
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}


	// lgas
	$scope.getLgaByState = function(state){
		if( $scope.stateEditId !== undefined ) return;

		$http.get('/drad/lga?state_id='+state)
			.success(function(res){
				$scope.lgaEditId = undefined;
				$scope.currentState = state;
				$scope.data.lgas = res.data.lgas;
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.setLgaEditId = function(id){
		$scope.lgaEditId = id;
	}

	$scope.createLga = function(lgaName){
		var lga = {name: lgaName, state: $scope.currentState};
		$http.post('/drad/lga', lga)
			.success(function(res){
				console.log(res);
				$scope.getLgaByState($scope.currentState);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.updateLgaName = function(lga){
		$http.put('/drad/lga/'+lga.id, lga)
			.success(function(res){
				$scope.setLgaEditId( undefined );
				$scope.getLgaByState($scope.currentState);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteLga = function(lgaId){
		$http.delete('drad/lga/'+lgaId)
			.success(function(res){
				console.log(res);
				$scope.lgaEditId = undefined;
				$scope.getLgaByState($scope.currentState);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	//towns
	$scope.getTownByLga = function(lga){
		console.log('lga:', lga);
		if( $scope.lgaEditId !== undefined ) return;

		$http.get('/drad/town?lga_id='+lga)
			.success(function(res){
				$scope.townEditId = undefined;
				$scope.currentLga = lga;
				$scope.data.towns = res.data.towns;
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.setTownEditId = function(id){
		$scope.townEditId = id;
	}

	$scope.createTown = function(townName){
		var town = {name: townName, lga_id: $scope.currentLga};
		$http.post('/drad/town', town)
			.success(function(res){
				console.log(res);
				$scope.getTownByLga($scope.currentLga);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.updateTownName = function(town){
		$http.put('/drad/town/'+town.id, town)
			.success(function(res){
				$scope.setTownEditId( undefined );
				$scope.getTownByLga($scope.currentLga);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteTown = function(townId){
		$http.delete('drad/town/'+townId)
			.success(function(res){
				console.log(res);
				$scope.townEditId = undefined;
				$scope.getTownByLga($scope.currentLga);
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}
}]);


//insurance management controller
angular.module('docradius').controller('insuranceController' ,[ '$scope', '$http', '$state', function($scope, $http, $state){
	$scope.data = {};

	$scope.forms = {};

	$scope.viewData = {};

	$scope.insuranceEditId = undefined;
	$scope.viewData.currentInsurance = undefined;

	$scope.policyEditId = undefined;

	$scope.compareId = function(id1, id2){
		if(id1 == id2){
			return true;
		}else{
			return false;
		}
	}

	//insurance

	$scope.setInsuranceEditId = function (insuranceId) {
		$scope.insuranceEditId = insuranceId;
	}

	$scope.setCurrentInsurance = function(insuranceId){
		$state.go('panel.insurance.manageInsurance', { insurance: insuranceId });
	}

	$scope.getInsurance = function(){
		$http.get('/drad/insurance')
			.success(function (res) {
				$scope.data.insurance = res.data.insurances;
			})
			.error(function (res) {
				console.log(res);
				alert('error');
			});
	}

	$scope.createInsurance = function(newInsurance){
		$http.post('/drad/insurance', { name: newInsurance})
			.success( function (res) {
				$scope.getInsurance();
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			})
	}

	$scope.updateInsuranceName = function (insurance) {
		$http.put('/drad/insurance/'+insurance.id, insurance)
			.success( function (res) {
				$scope.insuranceEditId = undefined;
				$scope.getInsurance();
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			});
	}

	$scope.deleteInsurance = function (insuranceId){
		$http.delete('/drad/insurance/' + insuranceId)
			.success( function (res) {
				$scope.insuranceEditId = undefined;
				$scope.getInsurance();
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			});
	}

	//insurance policies
	$scope.setPolicyEditId = function (policyId) {
		$scope.policyEditId = policyId;
	}

	$scope.getPolicyByInsurance = function(insuranceId){
		$http.get('/drad/insurancePlan?insurance_id='+insuranceId)
			.success(function (res) {
				$scope.data.policy = res.data.insurance_plans;
			})
			.error(function (res) {
				console.log(res);
				alert('error');
			});
	}

	$scope.createPolicy = function(newPolicy, insurance_id){
		$http.post('/drad/insurancePlan', { name: newPolicy, insurance_id: insurance_id})
			.success( function (res) {
				$scope.getPolicyByInsurance($scope.viewData.currentInsurance);
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			})
	}

	$scope.updatePolicyName = function (policy) {
		var updateVars = { name: policy.name, insurance_id: $scope.viewData.currentInsurance };
		$http.put('/drad/insurancePlan/'+policy.id, updateVars)
			.success( function (res) {
				$scope.policyEditId = undefined;
				$scope.getPolicy($scope.viewData.currentInsurance);
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			});
	}

	$scope.deletePolicy = function (policyId){
		$http.delete('/drad/insurancePlan/' + policyId)
			.success( function (res) {
				$scope.policyEditId = undefined;
				$scope.getPolicyByInsurance($scope.viewData.currentInsurance);
			})
			.error( function (res) {
				console.log(res);
				alert('error');
			});
	}

	$scope.$watch('viewData.currentInsurance', function(newValue){
		if(newValue === undefined || isNaN(newValue)){
			$scope.data.policy = undefined;
		}else{
			$scope.getPolicyByInsurance(newValue);
		}
	});

}]);
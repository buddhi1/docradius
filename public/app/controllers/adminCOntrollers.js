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

	$scope.stateEditIndex;

	$scope.compareIndex = function(idx1, idx2){
		if(idx1 == idx2){
			return true;
		}else{
			return false;
		}
	};

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

	$scope.setStateEditIndex = function(index){
		$scope.stateEditIndex = index;
	}

	$scope.updateStateName = function(state){
		$http.put('/drad/state/'+state.id, state)
			.success(function(res){
				console.log(res);
				$scope.getStates();
			})
			.error(function(res){
				console.log(res);
				alert('error');
			});
	}
}]);